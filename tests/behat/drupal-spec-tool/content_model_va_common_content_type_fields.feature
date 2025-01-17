@api
Feature: Content model: VA Common Content Type fields
  In order to enter structured content into my site
  As a content editor
  I want to have content type fields that reflect my content model.

  @dst @field_type @content_type_fields @dstfields @va_common
     Scenario: Fields
       Then exactly the following fields should exist for bundles "centralized_content" of entity type node
       | Type | Bundle | Field label | Machine name | Field type | Required | Cardinality | Form widget | Translatable |
| Content type | Centralized Content | Content | field_content_block | Entity reference revisions |  | Unlimited | Paragraphs Browser EXPERIMENTAL | Translatable |
| Content type | Centralized Content | Section | field_administration | Entity reference | Required | 1 | Select list | Translatable |
| Content type | Centralized Content | Purpose | body | Text (formatted, long, with summary) |  | 1 | Textarea with a summary and counter | Translatable |
| Content type | Centralized Content | Product | field_product | Entity reference |  | 1 | Select list | Translatable |
| Content type | Centralized Content | Applied to | field_applied_to | Text (plain) |  | 1 | Textfield |  |
