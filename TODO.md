# Documentation des Modules avec Annotations OpenAPI

## Module Inscription
- [x] PendingStudentController
  - [x] index - GET /api/pending-students
  - [x] store - POST /api/pending-students
  - [x] show - GET /api/pending-students/{pendingStudent}
  - [x] update - PUT /api/pending-students/{pendingStudent}
  - [x] destroy - DELETE /api/pending-students/{pendingStudent}
  - [x] submitDocuments - POST /api/pending-students/{pendingStudent}/documents
  - [x] getDocuments - GET /api/pending-students/{pendingStudent}/documents

- [x] SubmissionController
  - [x] getActiveSubmissionPeriods - GET /api/submissions/active-periods
  - [x] getActiveReclamationPeriods - GET /api/submissions/active-reclamation-periods
  - [x] checkSubmissionStatus - POST /api/submissions/check-status
  - [x] checkReclamationStatus - POST /api/submissions/check-reclamation-status
  - [x] getAcademicYears - GET /api/academic-years
  - [x] getAcademicYear - GET /api/academic-years/{academicYear}

## Module Stockage
 - [x] FileController
   - [x] index - GET /api/files
   - [x] store - POST /api/files
   - [x] show - GET /api/files/{file}
   - [x] update - PUT /api/files/{file}
   - [x] destroy - DELETE /api/files/{file}
   - [x] download - GET /api/files/{file}/download
   - [x] changeVisibility - POST /api/files/{file}/visibility
   - [x] lock - POST /api/files/{file}/lock
   - [x] unlock - POST /api/files/{file}/unlock
   - [x] activities - GET /api/files/{file}/activities
   - [x] publicFiles - GET /api/files/public

 - [x] FilePermissionController
   - [x] index - GET /api/files/{file}/permissions
   - [x] grant - POST /api/files/{file}/permissions/grant
   - [x] revoke - POST /api/files/{file}/permissions/revoke
   - [x] check - POST /api/files/{file}/permissions/check

 - [x] FileShareController
   - [x] index - GET /api/files/{file}/shares
   - [x] store - POST /api/files/{file}/shares
   - [x] show - GET /api/files/{file}/shares/{share}
   - [x] deactivate - POST /api/files/{file}/shares/{share}/deactivate
   - [x] destroy - DELETE /api/files/{file}/shares/{share}
   - [x] access - GET /api/files/share/{token}
   - [x] download - GET /api/files/share/{token}/download

## Module Finance
- [ ] Aucun contrôleur défini actuellement - modèles seulement (Amount, Exoneration, Transaction)

## Schémas de Modèles Ajoutés
- [x] PendingStudent
- [x] EntryLevel
- [x] EntryDiploma
- [x] SubmissionPeriod
- [x] ReclamationPeriod
- [x] AcademicYear
- [x] File
- [x] FilePermission
- [x] FileShare
- [x] FileActivity
- [x] User
- [x] Role
- [x] PaginationMeta
