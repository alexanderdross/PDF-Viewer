import { useEffect } from "react";

/**
 * Injects a JSON-LD schema script into the document head.
 * Automatically cleans up on unmount.
 * 
 * @param schemaId - Unique identifier for the schema script element
 * @param schema - The JSON-LD schema object to inject
 */
export function useJsonLd(schemaId: string, schema: Record<string, unknown> | null) {
  useEffect(() => {
    if (!schema) return;

    const script = document.createElement("script");
    script.type = "application/ld+json";
    script.id = schemaId;
    script.textContent = JSON.stringify(schema);

    // Remove existing script with same ID before adding new one
    const existing = document.getElementById(schemaId);
    if (existing) existing.remove();
    document.head.appendChild(script);

    return () => {
      const element = document.getElementById(schemaId);
      if (element) element.remove();
    };
  }, [schemaId, schema]);
}
