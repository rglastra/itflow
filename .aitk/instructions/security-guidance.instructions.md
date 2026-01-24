---
description: Security best practices and vulnerability patterns to avoid
applyTo: '**/*.{js,ts,jsx,tsx,html,py,php}'
---

# Security Guidance

Monitor and prevent common security vulnerabilities when writing or modifying code. Always consider security implications before implementing functionality.

## Critical Security Patterns to Avoid

### 1. Command Injection
**Risk**: Arbitrary command execution on the server

**Dangerous Patterns**:
```javascript
// NEVER DO THIS
exec(`command ${userInput}`)
child_process.exec(userInput)
eval(userInput)
```

```python
# NEVER DO THIS
os.system(userInput)
subprocess.call(userInput, shell=True)
exec(userInput)
```

**Safe Alternatives**:
- Use parameterized commands
- Validate and sanitize all input
- Use allow-lists for permitted values
- Avoid shell=True in subprocess calls

### 2. Cross-Site Scripting (XSS)
**Risk**: Malicious scripts executed in user browsers

**Dangerous Patterns**:
```javascript
// NEVER DO THIS
element.innerHTML = userInput
document.write(userInput)
dangerouslySetInnerHTML={{ __html: userInput }}
```

```html
<!-- NEVER DO THIS -->
<div>${userInput}</div>
<script>${userInput}</script>
```

**Safe Alternatives**:
- Use textContent instead of innerHTML
- Properly escape HTML entities
- Use Content Security Policy headers
- Sanitize user input with trusted libraries (DOMPurify)
- Use framework-provided safe rendering methods

### 3. Dangerous HTML Patterns
**Risk**: Script injection and DOM-based attacks

**Dangerous Patterns**:
```html
<!-- NEVER DO THIS -->
<iframe src="${userInput}"></iframe>
<a href="javascript:${userInput}">
<img src="x" onerror="${userInput}">
<form action="${userInput}">
```

**Safe Alternatives**:
- Validate URLs against allow-lists
- Use URL parsing to verify schemes
- Avoid javascript: protocol in URLs
- Sanitize event handlers

### 4. Insecure Deserialization (Python)
**Risk**: Arbitrary code execution through malicious serialized data

**Dangerous Patterns**:
```python
# NEVER DO THIS
pickle.loads(untrusted_data)
pickle.load(untrusted_file)
yaml.load(untrusted_data)  # without safe_load
```

**Safe Alternatives**:
- Use JSON instead of pickle when possible
- Use yaml.safe_load() instead of yaml.load()
- Validate data structure after deserialization
- Use signing/encryption for serialized data

### 5. SQL Injection
**Risk**: Unauthorized database access and data manipulation

**Dangerous Patterns**:
```javascript
// NEVER DO THIS
db.query(`SELECT * FROM users WHERE id = ${userId}`)
```

```python
# NEVER DO THIS
cursor.execute(f"SELECT * FROM users WHERE id = {user_id}")
```

**Safe Alternatives**:
- Always use parameterized queries/prepared statements
- Use ORM methods that handle escaping
- Never concatenate user input into SQL

### 6. Path Traversal
**Risk**: Unauthorized file system access

**Dangerous Patterns**:
```javascript
// NEVER DO THIS
fs.readFile(`./uploads/${userFilename}`)
res.sendFile(userPath)
```

**Safe Alternatives**:
- Validate file paths against allow-lists
- Use path.normalize() and verify results
- Check that resolved path is within expected directory
- Never trust user-provided file paths directly

### 7. Insecure Randomness
**Risk**: Predictable values for security-critical operations

**Dangerous Patterns**:
```javascript
// NEVER DO THIS for security
Math.random()  // for tokens, session IDs, etc.
```

```python
# NEVER DO THIS for security
random.random()  # for tokens, passwords, etc.
```

**Safe Alternatives**:
- Use crypto.randomBytes() (Node.js)
- Use secrets module (Python)
- Use cryptographically secure random generators

### 8. Hardcoded Secrets
**Risk**: Exposed credentials and API keys

**Dangerous Patterns**:
```javascript
// NEVER DO THIS
const API_KEY = "sk-1234567890abcdef";
const password = "admin123";
```

**Safe Alternatives**:
- Use environment variables
- Use secret management services (AWS Secrets Manager, etc.)
- Use .env files (excluded from git)
- Never commit secrets to version control

### 9. Missing Authentication/Authorization
**Risk**: Unauthorized access to protected resources

**Checklist**:
- Verify user authentication before protected operations
- Check authorization for each resource access
- Don't rely on client-side checks alone
- Implement proper session management
- Use principle of least privilege

## Security Best Practices

### Input Validation
- Validate all user input on the server side
- Use allow-lists (not deny-lists) when possible
- Validate data types, formats, and ranges
- Reject invalid input, don't try to clean it

### Output Encoding
- Encode output based on context (HTML, URL, JavaScript, etc.)
- Use framework-provided encoding functions
- Don't trust client-side encoding

### Authentication & Sessions
- Use secure session management
- Implement proper password hashing (bcrypt, Argon2)
- Use HTTPS for all sensitive communications
- Implement rate limiting and account lockouts
- Use secure, httpOnly, sameSite cookies

### Static Sites (Coolify/Hosting)
- Implement Content Security Policy (CSP) headers
- Use HTTPS only
- Set security headers (X-Frame-Options, X-Content-Type-Options)
- Sanitize any user-generated content
- Validate forms on both client and server
- Use CORS properly if making API calls

## Pre-Implementation Security Checklist

Before implementing functionality, ask:
- [ ] Does this accept user input? How is it validated?
- [ ] Could this execute code? Is it properly sandboxed?
- [ ] Does this access files? Are paths validated?
- [ ] Does this make database queries? Are they parameterized?
- [ ] Does this generate HTML? Is output encoded?
- [ ] Does this handle sensitive data? Is it protected?
- [ ] Could this be vulnerable to injection attacks?
- [ ] Are errors logged without exposing sensitive info?

## When Editing Security-Sensitive Code

**STOP and verify**:
1. What security implications does this change have?
2. Could this introduce any of the patterns listed above?
3. Are all inputs validated and outputs encoded?
4. Is this change consistent with security best practices?

## Resources

- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [OWASP Cheat Sheet Series](https://cheatsheetseries.owasp.org/)
- Content Security Policy documentation
- Framework-specific security guides
