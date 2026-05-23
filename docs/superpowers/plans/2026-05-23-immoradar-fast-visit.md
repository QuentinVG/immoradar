# ImmoRadar Fast Visit Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Reduce property-entry and visit friction while preserving full decision depth.

**Architecture:** Keep the existing Laravel MVC structure. Add defaults in the property request, mode-aware question filtering and summary logic in the visit controller, then simplify Blade views through progressive disclosure.

**Tech Stack:** Laravel, Blade, Alpine.js, Tailwind CSS, PHPUnit feature tests.

---

### Task 1: Express Property Creation

**Files:**
- Modify: `app/Http/Requests/StorePropertyRequest.php`
- Modify: `resources/views/properties/_form.blade.php`
- Test: `tests/Feature/FastVisitWorkflowTest.php`

- [ ] Write a failing feature test that posts only `title` and `city` and expects default values for `property_type`, `transaction_type`, `dpe`, and `status`.
- [ ] Add create-only request defaults for those fields.
- [ ] Collapse non-essential property fields behind advanced sections on create while leaving edit complete.

### Task 2: Visit Modes And Critical Missing Count

**Files:**
- Modify: `app/Http/Controllers/VisitChecklistController.php`
- Modify: `resources/views/visit/edit.blade.php`
- Test: `tests/Feature/FastVisitWorkflowTest.php`

- [ ] Write failing tests proving default visit mode hides non-express questions and full mode shows them.
- [ ] Write a failing test proving unanswered critical visible questions count as critical missing.
- [ ] Add `express`, `standard`, and `full` mode filtering.
- [ ] Make autosave totals mode-aware.
- [ ] Add mode controls and an "before leaving" action panel.

### Task 3: Verification And Ship

**Files:**
- Modify as needed from Tasks 1 and 2.

- [ ] Run focused feature tests.
- [ ] Run full PHPUnit suite.
- [ ] Run build, Pint, and PHPStan.
- [ ] Verify the local UI in browser/screenshots.
- [ ] Commit and push to `origin/main`.
