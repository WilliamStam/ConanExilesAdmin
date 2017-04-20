SELECT fields.* FROM (
SELECT companies.*,
  MAX(CASE WHEN companies_data.fieldID = 1 THEN COALESCE(companies_fields_data.value,companies_data.value) ELSE NULL END) AS `field1`,
  MAX(CASE WHEN companies_data.fieldID = 2 THEN COALESCE(companies_fields_data.value,companies_data.value) ELSE NULL END) AS `field2`,
  MAX(CASE WHEN companies_data.fieldID = 3 THEN COALESCE(companies_fields_data.value,companies_data.value) ELSE NULL END) AS `field3`,
  MAX(CASE WHEN companies_data.fieldID = 4 THEN COALESCE(companies_fields_data.value,companies_data.value) ELSE NULL END) AS `field4`,
  MAX(CASE WHEN companies_data.fieldID = 5 THEN COALESCE(companies_fields_data.value,companies_data.value) ELSE NULL END) AS `field5`
FROM (companies LEFT JOIN companies_data ON companies.ID = companies_data.parentID) LEFT JOIN companies_fields ON companies_fields.ID = companies_data.fieldID LEFT JOIN companies_fields_data ON companies_fields_data.fieldID = companies_fields.ID AND companies_fields_data.ID = companies_data.value
WHERE _deleted='0'
GROUP BY companies.ID
) fields


ORDER BY ID ASC, field5 DESC, date_in DESC ;