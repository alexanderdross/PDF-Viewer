/**
 * Unit Tests - @pdf-embed-seo/core defaults and constants
 */

import { describe, it, expect } from 'vitest';
import {
  DEFAULT_SETTINGS,
  DEFAULT_VIEWER_OPTIONS,
  DEFAULT_ARCHIVE_OPTIONS,
  PDFJS_CONFIG,
  API_CONFIG,
  SCHEMA_CONFIG,
  SUPPORTED_MIME_TYPES,
  CSS_CLASSES,
  EVENTS,
} from '../constants/defaults';

describe('@pdf-embed-seo/core constants', () => {
  describe('DEFAULT_SETTINGS', () => {
    it('should have correct default viewer theme', () => {
      expect(DEFAULT_SETTINGS.viewerTheme).toBe('light');
    });

    it('should have correct default height', () => {
      expect(DEFAULT_SETTINGS.defaultHeight).toBe('800px');
    });

    it('should allow download and print by default', () => {
      expect(DEFAULT_SETTINGS.defaultAllowDownload).toBe(true);
      expect(DEFAULT_SETTINGS.defaultAllowPrint).toBe(true);
    });

    it('should default to grid archive display', () => {
      expect(DEFAULT_SETTINGS.archiveDisplayMode).toBe('grid');
      expect(DEFAULT_SETTINGS.archivePerPage).toBe(12);
    });

    it('should not be premium by default', () => {
      expect(DEFAULT_SETTINGS.isPremium).toBe(false);
    });
  });

  describe('DEFAULT_VIEWER_OPTIONS', () => {
    it('should have correct dimensions', () => {
      expect(DEFAULT_VIEWER_OPTIONS.width).toBe('100%');
      expect(DEFAULT_VIEWER_OPTIONS.height).toBe('800px');
    });

    it('should show toolbar and navigation by default', () => {
      expect(DEFAULT_VIEWER_OPTIONS.showToolbar).toBe(true);
      expect(DEFAULT_VIEWER_OPTIONS.showPageNav).toBe(true);
      expect(DEFAULT_VIEWER_OPTIONS.showZoom).toBe(true);
    });

    it('should start at page 1 with auto zoom', () => {
      expect(DEFAULT_VIEWER_OPTIONS.initialPage).toBe(1);
      expect(DEFAULT_VIEWER_OPTIONS.initialZoom).toBe('auto');
    });

    it('should disable premium features by default', () => {
      expect(DEFAULT_VIEWER_OPTIONS.enableSearch).toBe(false);
      expect(DEFAULT_VIEWER_OPTIONS.enableBookmarks).toBe(false);
      expect(DEFAULT_VIEWER_OPTIONS.enableProgress).toBe(false);
    });
  });

  describe('DEFAULT_ARCHIVE_OPTIONS', () => {
    it('should default to grid view with 3 columns', () => {
      expect(DEFAULT_ARCHIVE_OPTIONS.view).toBe('grid');
      expect(DEFAULT_ARCHIVE_OPTIONS.columns).toBe(3);
    });

    it('should show essential UI elements', () => {
      expect(DEFAULT_ARCHIVE_OPTIONS.showThumbnails).toBe(true);
      expect(DEFAULT_ARCHIVE_OPTIONS.showPagination).toBe(true);
      expect(DEFAULT_ARCHIVE_OPTIONS.showSearch).toBe(true);
    });

    it('should not show premium filters by default', () => {
      expect(DEFAULT_ARCHIVE_OPTIONS.showCategoryFilter).toBe(false);
      expect(DEFAULT_ARCHIVE_OPTIONS.showTagFilter).toBe(false);
    });
  });

  describe('API_CONFIG', () => {
    it('should have WordPress API path', () => {
      expect(API_CONFIG.wordpressPath).toBe('/wp-json/pdf-embed-seo/v1');
    });

    it('should have Drupal API path', () => {
      expect(API_CONFIG.drupalPath).toBe('/api/pdf-embed-seo/v1');
    });

    it('should have reasonable timeout', () => {
      expect(API_CONFIG.timeout).toBeGreaterThan(0);
      expect(API_CONFIG.timeout).toBeLessThanOrEqual(60000);
    });

    it('should have valid pagination limits', () => {
      expect(API_CONFIG.defaultPerPage).toBeGreaterThan(0);
      expect(API_CONFIG.maxPerPage).toBeGreaterThanOrEqual(API_CONFIG.defaultPerPage);
    });
  });

  describe('PDFJS_CONFIG', () => {
    it('should have a version string', () => {
      expect(PDFJS_CONFIG.version).toBeTruthy();
    });

    it('should have a CDN base URL', () => {
      expect(PDFJS_CONFIG.cdnBase).toContain('https://');
    });

    it('should reference worker file', () => {
      expect(PDFJS_CONFIG.workerFile).toContain('worker');
    });
  });

  describe('SCHEMA_CONFIG', () => {
    it('should default to DigitalDocument type', () => {
      expect(SCHEMA_CONFIG.defaultType).toBe('DigitalDocument');
    });

    it('should have accessibility features', () => {
      expect(SCHEMA_CONFIG.accessibilityFeatures.length).toBeGreaterThan(0);
    });
  });

  describe('SUPPORTED_MIME_TYPES', () => {
    it('should include PDF mime type', () => {
      expect(SUPPORTED_MIME_TYPES).toContain('application/pdf');
    });
  });

  describe('CSS_CLASSES', () => {
    it('should have viewer classes', () => {
      expect(CSS_CLASSES.viewer.wrapper).toBeTruthy();
      expect(CSS_CLASSES.viewer.toolbar).toBeTruthy();
      expect(CSS_CLASSES.viewer.canvas).toBeTruthy();
    });

    it('should have archive classes', () => {
      expect(CSS_CLASSES.archive.wrapper).toBeTruthy();
      expect(CSS_CLASSES.archive.grid).toBeTruthy();
      expect(CSS_CLASSES.archive.list).toBeTruthy();
    });

    it('should have theme classes', () => {
      expect(CSS_CLASSES.viewer.themeLight).toContain('light');
      expect(CSS_CLASSES.viewer.themeDark).toContain('dark');
    });
  });

  describe('EVENTS', () => {
    it('should have all required event names', () => {
      expect(EVENTS.documentLoaded).toBeTruthy();
      expect(EVENTS.pageChanged).toBeTruthy();
      expect(EVENTS.zoomChanged).toBeTruthy();
      expect(EVENTS.errorOccurred).toBeTruthy();
    });
  });
});
