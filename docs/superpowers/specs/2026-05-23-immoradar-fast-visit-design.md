# ImmoRadar Fast Visit Design

## Goal

Make ImmoRadar faster for the first property and the live visit without removing the deeper analysis screens.

## Scope

- Property creation starts in an express mode: title and city are enough to create a record, with sensible defaults for internal fields.
- The full property form remains available through collapsed advanced sections.
- The visit screen opens in an express checklist by default and offers standard/full modes.
- Critical visit questions count as missing until they are positively answered or marked not applicable.

## Non Goals

- No listing URL extraction.
- No document upload.
- No investment yield model.
- No redesign of the scoring formulas outside visit progress and critical missing counts.

## UX Rules

- Create first, complete later.
- In a visit, prefer taps over typing.
- Show the smallest checklist that can protect the user from a bad decision.
- Keep offer readiness strict, but do not use it as the first triage verdict.
