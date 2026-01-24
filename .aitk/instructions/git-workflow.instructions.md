---
description: Git workflow and pull request practices for this repository
applyTo: '**'
---

# Git Workflow Rules

## Branch Strategy

**NEVER commit directly to main.** Always use feature branches.

### Workflow Steps

1. **Create a feature branch** for any new work:
   - Use descriptive branch names (e.g., `feature/add-homepage`, `fix/navigation-bug`)
   
2. **Commit your work** to the feature branch

3. **Create a Pull Request** to merge into main

4. **Merge the PR** to main after review/approval

## Before Committing Additional Work

**ALWAYS check the status of related Pull Requests:**
- Check if a PR for the current feature branch already exists
- Verify if the PR is open or closed
- If a PR is open, ensure new commits are appropriate for that PR
- If a PR is closed/merged, create a new feature branch for new work

## Commands Reference

```bash
# Check current branch
git branch --show-current

# List all PRs (using GitHub CLI or MCP tools)
gh pr list

# Check PR status
gh pr view <pr-number>
```

## Summary

✅ DO: Feature branch → Commit → PR → Merge to main
❌ DON'T: Commit directly to main
⚠️ ALWAYS: Check PR status before additional commits
