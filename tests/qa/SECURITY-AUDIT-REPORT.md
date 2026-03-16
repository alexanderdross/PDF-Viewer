# Drupal PDF Embed SEO - Security Audit Report

**Date:** 2026-03-16
**Scope:** All 3 tiers (Free, Premium, Pro+ Enterprise)
**Methodology:** Static code analysis, OWASP Top 10 review
**Status:** All identified vulnerabilities FIXED

---

## Findings Summary

| ID | Severity | Module | Issue | Status |
|---|---|---|---|---|
| SEC-001 | HIGH | Pro+ | XSS via `addslashes()` in WhiteLabel JS output | FIXED |
| SEC-002 | HIGH | Pro+ | Broken permission logic in AnnotationsApiController | FIXED |
| SEC-003 | MEDIUM | Premium | Loose equality (`!=`) in expiring link validation | FIXED |
| SEC-004 | LOW | Pro+ | Custom CSS/JS injection via white-label settings | ACCEPTED |
| SEC-005 | INFO | Free | JSON-LD `json_encode\|raw` pattern in Twig | SAFE |

---

## SEC-001: XSS in WhiteLabel JavaScript Output

**Severity:** HIGH
**CVSS:** 6.1 (Medium) - requires admin to configure malicious company name
**File:** `modules/pdf_embed_seo_pro_plus/src/Service/WhiteLabel.php:158`
**CWE:** CWE-79 (Cross-site Scripting)

### Description

The `getJavaScript()` method used `addslashes()` to escape a company name before embedding it in a JavaScript string. `addslashes()` only escapes `'`, `"`, `\`, and NUL byte - it does not escape `</script>`, backticks, or other JS-significant characters.

### Attack Vector

An admin with access to white-label settings could set the company name to:
```
</script><script>alert(document.cookie)</script>
```

This would break out of the script context and execute arbitrary JavaScript on every page where the viewer loads.

### Fix Applied

```php
// Replaced addslashes() with json_encode()
$company = json_encode($config['company_name']);
$js .= "el.setAttribute('data-company', {$company});";
```

`json_encode()` properly escapes all special characters including `</`, producing `\u003C\/script\u003E` which is safe in both HTML and JS contexts.

---

## SEC-002: Broken Permission Logic in Annotations API

**Severity:** HIGH
**CVSS:** 5.3 (Medium) - authenticated users could edit/delete others' annotations
**File:** `modules/pdf_embed_seo_pro_plus/src/Controller/AnnotationsApiController.php:168-183, 221-236`
**CWE:** CWE-863 (Incorrect Authorization)

### Description

The `updateAnnotation()` and `deleteAnnotation()` methods had a flawed permission check that allowed annotation owners to modify their annotations without the required `edit own pdf annotations` permission.

### Original Code (Broken)

```php
if ($current_uid !== $owner_uid && !$this->currentUser()->hasPermission('edit any pdf annotations')) {
  if (!$this->currentUser()->hasPermission('edit own pdf annotations')) {
    return new JsonResponse(['error' => '...'], 403);
  }
}
```

**Logic flaw:** When `$current_uid === $owner_uid`, the outer `if` is false, so the permission check is skipped entirely. The owner always gets through regardless of permissions.

### Fix Applied

```php
$is_owner = ($current_uid === $owner_uid);
if ($is_owner && !$this->currentUser()->hasPermission('edit own pdf annotations')) {
  return new JsonResponse(['success' => FALSE, 'message' => 'Permission denied.'], 403);
}
if (!$is_owner && !$this->currentUser()->hasPermission('edit any pdf annotations')) {
  return new JsonResponse(['success' => FALSE, 'message' => 'Permission denied.'], 403);
}
```

---

## SEC-003: Loose Equality in Expiring Link Validation

**Severity:** MEDIUM
**CVSS:** 3.7 (Low) - theoretical type juggling
**File:** `modules/pdf_embed_seo_premium/src/Controller/PdfPremiumApiController.php:640`
**CWE:** CWE-1025 (Comparison Using Wrong Factors)

### Description

The expiring link validation compared a document ID from the token with the actual document ID using loose equality (`!=`). In PHP, loose comparisons can produce unexpected results with numeric strings (e.g., `"1e0" == "1"` is true).

### Fix Applied

```php
// Before: if ($doc_id != $pdf_document->id()) {
// After:
if ((int) $doc_id !== (int) $pdf_document->id()) {
```

---

## SEC-004: Custom CSS/JS Injection via White-Label (Accepted Risk)

**Severity:** LOW
**File:** `modules/pdf_embed_seo_pro_plus/src/Service/WhiteLabel.php:129-131, 163-165`

### Description

The white-label feature allows admins to inject arbitrary CSS and JavaScript. This is by design (enterprise branding feature) but could be abused by a compromised admin account.

### Mitigation

- Feature is gated behind `administer pdf embed seo` permission
- Only admin users can configure white-label settings
- Accepted risk as it's a core feature requirement for enterprise customers

---

## SEC-005: JSON-LD Output in Twig Templates (Safe)

**Severity:** INFO (False Positive)
**File:** `templates/pdf-document.html.twig`

### Description

The pattern `{{ schema_data|json_encode|raw }}` was flagged as potentially unsafe because `|raw` disables Twig auto-escaping.

### Analysis

Drupal's Twig `json_encode` filter calls PHP's `json_encode()` with `JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT` flags, which encode:
- `<` as `\u003C`
- `>` as `\u003E`
- `'` as `\u0027`
- `&` as `\u0026`
- `"` as `\u0022`

This makes it safe to output inside a `<script type="application/ld+json">` tag. The `|raw` filter is necessary because the output is already properly escaped JSON.

---

## Security Best Practices Verified

| Practice | Free | Premium | Pro+ |
|---|---|---|---|
| SQL injection prevention (parameterized queries) | PASS | PASS | PASS |
| CSRF protection on POST endpoints | PASS | PASS | PASS |
| Permission checks on admin routes | PASS | PASS | PASS |
| Password hashing (bcrypt) | N/A | PASS | PASS |
| Input sanitization | PASS | PASS | PASS |
| Output escaping | PASS | PASS | PASS (fixed) |
| Rate limiting | N/A | PASS | PASS |
| Webhook signature verification (HMAC-SHA256) | N/A | N/A | PASS |
| IP anonymization (GDPR) | N/A | N/A | PASS |
| Audit logging | N/A | N/A | PASS |

---

## Recommendations

1. **Add Content-Security-Policy headers** when serving PDF viewer pages to further mitigate XSS
2. **Consider CSP nonce-based script loading** for white-label custom JS
3. **Add automated security scanning** (e.g., PHPStan security rules) to CI pipeline
4. **Implement webhook payload size limits** to prevent memory exhaustion
5. **Add rate limiting to annotation API** endpoints to prevent abuse
