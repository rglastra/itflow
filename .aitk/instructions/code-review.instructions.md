---
description: Code review guidelines and quality checks for pull requests
applyTo: '**'
---

# Code Review Guidelines

These guidelines help maintain code quality and catch issues before merging to main. Apply these checks when reviewing code or creating pull requests.

## Review Perspectives

### 1. Documentation Compliance
Check if code follows any documented guidelines in the repository:
- README.md conventions
- CONTRIBUTING.md requirements
- Any project-specific style guides
- API documentation accuracy

### 2. Bug Detection
Focus on changes introduced in the PR (not pre-existing issues):
- Logic errors and edge cases
- Off-by-one errors
- Null/undefined handling
- Type mismatches
- Resource leaks (memory, file handles, connections)
- Race conditions
- Infinite loops or recursion

### 3. Code Quality
- **Clarity**: Is the code easy to understand?
- **Naming**: Are variables, functions, and classes well-named?
- **Structure**: Is code properly organized?
- **Duplication**: Is there unnecessary code repetition?
- **Complexity**: Could this be simplified?

### 4. Security
Apply all patterns from security-guidance.instructions.md:
- Input validation
- Output encoding
- Authentication/authorization
- Injection vulnerabilities
- XSS risks

### 5. Performance
- Inefficient algorithms (O(n²) when O(n) possible)
- Unnecessary loops or operations
- Memory usage issues
- Network request optimization
- Caching opportunities

### 6. Testing
- Are critical paths tested?
- Are edge cases covered?
- Are error conditions tested?
- Are tests clear and maintainable?

## Confidence Scoring System

When identifying issues, assess confidence level:

- **0-25**: Probably not an issue, uncertain
- **25-50**: Might be an issue, needs clarification
- **50-75**: Likely an issue, but could be intentional
- **75-100**: Definitely an issue that should be fixed

**Only flag issues with 75+ confidence** unless specifically asked for comprehensive review.

## What NOT to Flag

### False Positives to Avoid
- Pre-existing issues not introduced in this PR
- Code that looks odd but is actually correct
- Pedantic nitpicks about style (unless violates documented guidelines)
- Issues that linters will catch automatically
- General quality suggestions (unless they impact functionality)
- Code with explicit comments explaining unusual patterns

### Trust Developer Intent
If code has:
- `// eslint-disable` or similar ignore comments
- Comments explaining why something is done a certain way
- Tests covering the unusual behavior

Then it's probably intentional. Only flag if there's a clear problem.

## Review Checklist

### Before Reviewing
- [ ] Is this a meaningful change? (Skip trivial/automated PRs)
- [ ] Is the PR description clear about what and why?
- [ ] Are there related issues or context to understand?

### During Review
- [ ] Does the code work correctly for intended use case?
- [ ] Are edge cases handled?
- [ ] Is error handling appropriate?
- [ ] Could this introduce security vulnerabilities?
- [ ] Is the code maintainable and clear?
- [ ] Are there tests for new functionality?
- [ ] Does it follow project conventions?

### After Review
- [ ] Are all high-confidence issues documented clearly?
- [ ] Are suggestions constructive and actionable?
- [ ] Is there anything positive to highlight?

## Providing Feedback

### Structure
When flagging an issue:

1. **What**: Clearly describe the problem
2. **Why**: Explain why it's a problem
3. **Where**: Reference specific lines/files
4. **How**: Suggest a solution (when applicable)
5. **Confidence**: Your confidence level (0-100)

### Example Format
```
Missing error handling for network request (Confidence: 85)

In src/api/client.ts lines 45-52, the fetch call doesn't handle network errors. 
If the request fails, this will throw an uncaught promise rejection.

Suggestion: Wrap in try-catch or add .catch() handler

try {
  const response = await fetch(url);
  return await response.json();
} catch (error) {
  console.error('Failed to fetch:', error);
  return null;
}
```

### Tone
- Be constructive, not critical
- Assume good intent
- Ask questions instead of making demands
- Highlight what's done well
- Focus on the code, not the person

## Common Issues by File Type

### JavaScript/TypeScript
- Async/await error handling
- Type safety (TypeScript)
- Memory leaks (event listeners, timers)
- Callback hell
- Promise handling

### HTML
- Semantic markup
- Accessibility (ARIA, alt text, keyboard nav)
- XSS vulnerabilities
- Forms validation
- SEO considerations

### CSS
- Specificity issues
- Responsive design
- Browser compatibility
- Performance (avoid expensive selectors)
- Maintainability (avoid magic numbers)

### Python
- Exception handling
- Resource management (file handles, connections)
- Type hints usage
- PEP 8 compliance
- Pickle/eval security

## Git History Context

When reviewing, consider:
- Why was this code written this way originally? (git blame)
- Has this area had bugs before?
- Who are the domain experts to consult?
- Are there related changes in project history?

## Integration with Workflow

### Before Creating PR
1. Self-review your changes
2. Run through this checklist
3. Fix obvious issues
4. Write clear PR description

### Before Merging PR
1. Review all feedback
2. Address high-confidence issues (75+)
3. Consider lower-confidence suggestions
4. Update tests if needed
5. Ensure CI passes

## Tips for Effective Reviews

- **Write specific guidelines**: Document project-specific patterns in README or CONTRIBUTING
- **Focus on high-impact issues**: Don't nitpick style unless it matters
- **Use confidence scores**: They help prioritize what's important
- **Iterate on guidelines**: Update docs based on recurring issues
- **Be consistent**: Apply the same standards to all PRs
- **Review promptly**: Don't let PRs go stale

## When to Skip Review

- Closed or merged PRs
- Draft PRs (unless feedback requested)
- Automated dependency updates (if tests pass)
- Trivial typo fixes
- Reverts of problematic changes
- Urgent hotfixes (review after merge)

## Tools & Automation

Complement manual review with:
- Linters (ESLint, Pylint, etc.)
- Type checkers (TypeScript, mypy)
- Security scanners
- Code coverage tools
- Automated tests

But remember: **Automated tools catch syntax, humans catch logic.**
