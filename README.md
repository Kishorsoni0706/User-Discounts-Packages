# 🎁 User Discounts Package

A reusable Laravel 10+ package for managing **user-level discounts**, with deterministic stacking, per-user usage caps, audit logging, concurrency safety, and full automated test suite.

---

## 📦 Features

- ✅ Composer-installable (PSR-4, versioned)
- ✅ Assign and revoke discounts per user
- ✅ Deterministic stacking logic
- ✅ Usage limits per user enforced
- ✅ Expired/inactive discounts ignored
- ✅ Safe against concurrent apply attempts
- ✅ Audit logs for assign, revoke, and apply
- ✅ Configurable rounding, stacking order, and cap
- ✅ Fully tested (unit + feature)

---

## 🔧 Installation

1. Require the package (assuming local path or published registry):

```bash
composer require onlineshopping/userdiscounts
