# Test Coverage Documentation

This document outlines the comprehensive test suite for the Talk Proposals API backend.

## Test Structure

### Unit Tests (`tests/Unit/`)

#### Models
- **UserTest.php** - Tests user model methods, relationships, and role checks
- **ProposalTest.php** - Tests proposal model relationships, scopes, and status checks
- **ReviewTest.php** - Tests review model relationships, scopes, and validation
- **TagTest.php** - Tests tag model relationships and search functionality

#### Enums
- **UserRoleTest.php** - Tests UserRole enum values and registration roles
- **ProposalStatusTest.php** - Tests ProposalStatus enum values
- **ReviewRatingTest.php** - Tests ReviewRating enum values, min, and max

#### Policies
- **ProposalPolicyTest.php** - Tests proposal authorization policies
- **ReviewPolicyTest.php** - Tests review authorization policies

### Feature/Integration Tests (`tests/Feature/`)

#### Authentication
- **AuthTest.php** - Tests registration, login, logout, and user info retrieval
- **AuthValidationTest.php** - Tests authentication validation rules

#### Proposals
- **ProposalTest.php** - Tests proposal CRUD operations
- **ProposalValidationTest.php** - Tests proposal validation rules
- **ProposalAuthorizationTest.php** - Tests proposal authorization rules
- **ProposalFileTest.php** - Tests proposal file download functionality
- **ProposalPaginationTest.php** - Tests proposal pagination

#### Reviews
- **ReviewTest.php** - Tests review CRUD operations
- **ReviewValidationTest.php** - Tests review validation rules

#### Tags
- **TagTest.php** - Tests tag listing, creation, and search

#### Admin
- **AdminProposalTest.php** - Tests admin-specific proposal operations

## Test Coverage Summary

### Unit Tests (24 tests)
- ✅ User model: 6 tests
- ✅ Proposal model: 7 tests
- ✅ Review model: 6 tests
- ✅ Tag model: 3 tests
- ✅ Enums: 5 tests
- ✅ Policies: 9 tests

### Feature Tests (71 tests)
- ✅ Authentication: 12 tests
- ✅ Proposals: 25 tests
- ✅ Reviews: 8 tests
- ✅ Tags: 5 tests
- ✅ Admin: 6 tests
- ✅ File Downloads: 4 tests
- ✅ Pagination: 4 tests
- ✅ Validation: 7 tests

## Running Tests

### Run All Tests
```bash
php artisan test
```

### Run Specific Test Suite
```bash
# Unit tests only
php artisan test --testsuite=Unit

# Feature tests only
php artisan test --testsuite=Feature
```

### Run Specific Test File
```bash
php artisan test tests/Feature/AuthTest.php
```

### Run Specific Test Method
```bash
php artisan test --filter test_user_can_register
```

### With Coverage (requires Xdebug)
```bash
php artisan test --coverage
```

## Test Categories

### Authorization Tests
- User role-based access control
- Resource ownership checks
- Admin-only operations
- Cross-user access restrictions

### Validation Tests
- Required field validation
- Data type validation
- File upload validation (type, size)
- Enum value validation
- Unique constraint validation

### Business Logic Tests
- Duplicate review prevention
- Proposal status transitions
- Tag creation/retrieval
- File download permissions
- Pagination limits

### Edge Cases
- Missing files
- Invalid IDs
- Empty collections
- Boundary values (min/max)
- Concurrent operations

## Test Best Practices

1. **Isolation**: Each test is independent and uses `RefreshDatabase`
2. **Naming**: Tests use descriptive names following `test_` prefix
3. **Assertions**: Multiple assertions per test where appropriate
4. **Factories**: Use model factories for test data generation
5. **Fakes**: Use `Storage::fake()` for file operations (where applicable)
6. **Acting As**: Use `actingAs()` for authenticated requests

## Continuous Integration

These tests are designed to run in CI/CD pipelines:
- All tests use in-memory SQLite database
- No external dependencies required
- Fast execution time
- Deterministic results

## Maintenance

When adding new features:
1. Add corresponding unit tests for models/enums
2. Add feature tests for new endpoints
3. Add validation tests for new form requests
4. Add authorization tests for new policies
5. Update this documentation

