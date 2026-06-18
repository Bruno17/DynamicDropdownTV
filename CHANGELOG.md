Changelog
================================================================================

- 1.4.0-pl (dual-compat fork by JNogueira)
    - Dual compatibility with MODX 2.x and 3.x under PHP 8.x
    - Class-based processors (*.class.php) — fixes MODX 3 "Requested processor not found"
    - Per-TV override processor lookup prefers .class.php, falls back to legacy .php
    - Processor-level manager authentication gate
    - MigX form-builder render fix on MODX 3 (Smarty fallback when controller is null)
    - Plugin-cache parse fix (strip leading <?php before persisting plugincode)
    - PHP 8.x hardening and MODX 3 API swaps (json_encode/json_decode, Ext.decode)
    - Connector requires authenticated manager session; XSS, path-traversal and template-injection hardening
    - Added Portuguese (pt) lexicon
    - Self-contained dual-compat build system

- 1.3
    - Release line by Bruno Perner

- 1.2
    - Checked/rewritten documentation
    - Checked/rewritten examples
    - Reviewed/changed tv properties
    - Added processor for input option values

- 1.1
    - Added dynamicdropdown_multiple for multiple select options

- 1.0
    - Initial public release
