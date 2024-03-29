/* First get list of Ids from this query */

SELECT TrackIT__Inventory__r.Id 
    FROM TrackIT__Inv_Location__c 
    WHERE TrackIT__Location__r.Id = 'a3W1U000000ith2UAA'

/* Paste into https://docs.google.com/spreadsheets/d/1tWSkFsFrvtOyA1xziiO7t6fdT7MKqo6Odl0mKGa5vJs/edit?usp=sharing */
/* Copy and paste into the WHERE IN clause in the following query */

SELECT 
    TrackIT__Inventory__r.Name,
    TrackIT__Location__r.Name,
    Quantity_Needed__c,
    TrackIT__Inventory__r.Indiv_Unit_of_Measurement_Description__c,
    TrackIT__Quantity__c,
    Restock_Point__c,
    Optimal_Quantity__c,
    Max_Storage_Capacity__c,
    TrackIT__Inventory__r.Image_for_ListView__c,
    TrackIT__Inventory__r.Manufacturer_Model__c,
    TrackIT__Inventory__r.Manufacturer_Product_Website__c,
    TrackIT__Inventory__r.Best_Bulk_Supplier__c,
    TrackIT__Inventory__r.Best_Bulk_Unit_Product_Website__c,
    TrackIT__Inventory__r.Best_Supplier_Bulk_Unit_Ordering_ID__c,
    TrackIT__Inventory__r.Best_Indiv_Item_Supplier__c,
    TrackIT__Inventory__r.Best_Indiv_Item_Product_Website__c,
    TrackIT__Inventory__r.Best_Supplier_Indiv_Item_Ordering_ID__c
FROM TrackIT__Inv_Location__c 
WHERE 
    TrackIT__Inventory__r.Id IN (
        'a3S1U000000ifWTUAY',
        'a3S1U000000ifWpUAI',
        'a3S1U000000ifWtUAI',
        'a3S1U000000ifWJUAY',
        'a3S1U000000ifYGUAY',
        'a3S1U000000ifWaUAI',
        'a3S1U000000ifXEUAY',
        'a3S1U000000ifewUAA',
        'a3S1U000000ifcEUAQ',
        'a3S1U000000ifdTUAQ',
        'a3S1U000000ifcvUAA',
        'a3S1U000000ifX9UAI',
        'a3S1U000000ifYMUAY',
        'a3S1U000000ifWVUAY',
        'a3S1U000000ifXcUAI',
        'a3S1U000000ifdrUAA',
        'a3S1U000000iff7UAA',
        'a3S1U000000ifWuUAI',
        'a3S1U000000igjmUAA',
        'a3S1U000000ifXWUAY',
        'a3S1U000000igrQUAQ',
        'a3S1U000000igrkUAA',
        'a3S1U000000ifYNUAY',
        'a3S1U000000ifc9UAA',
        'a3S1U000000ifYaUAI',
        'a3S1U000000ifcCUAQ',
        'a3S1U000000ifYLUAY',
        'a3S1U000000ifWgUAI',
        'a3S1U000000igq3UAA',
        'a3S1U000000ifYdUAI',
        'a3S1U000000ifWnUAI',
        'a3S1U000000ifeDUAQ',
        'a3S1U000000ifXgUAI',
        'a3S1U000000ifX8UAI',
        'a3S1U000000ifYCUAY',
        'a3S1U000000ifYYUAY',
        'a3S1U000000ifYnUAI',
        'a3S1U000000ifVkUAI',
        'a3S1U000000ifX3UAI',
        'a3S1U000000iff9UAA',
        'a3S1U000000igmfUAA',
        'a3S1U000000ifWbUAI',
        'a3S1U000000iffBUAQ',
        'a3S1U000000ifWvUAI',
        'a3S1U000000ifXmUAI',
        'a3S1U000000ifXNUAY',
        'a3S1U000000ifWRUAY',
        'a3S1U000000ifX2UAI',
        'a3S1U000000ifYTUAY',
        'a3S1U000000igruUAA',
        'a3S1U000000ifdVUAQ',
        'a3S1U000000ifWkUAI',
        'a3S1U000000ifYcUAI',
        'a3S1U000000ifYXUAY',
        'a3S1U000000igs9UAA',
        'a3S1U000000ifWxUAI',
        'a3S1U000000ifWwUAI',
        'a3S1U000000ifYAUAY',
        'a3S1U000000ifWhUAI',
        'a3S1U000000ifeMUAQ',
        'a3S1U000000ifWUUAY',
        'a3S1U000000ifelUAA',
        'a3S1U000000ifXIUAY',
        'a3S1U000000ifYHUAY',
        'a3S1U000000ifYUUAY',
        'a3S1U000000ifWjUAI',
        'a3S1U000000ifWcUAI',
        'a3S1U000000ifX6UAI',
        'a3S1U000000ifY4UAI',
        'a3S1U000000ifdQUAQ',
        'a3S1U000000ifXGUAY',
        'a3S1U000000ifWWUAY',
        'a3S1U000000ifXOUAY',
        'a3S1U000000ifXDUAY',
        'a3S1U000000ifY0UAI',
        'a3S1U000000ifWfUAI',
        'a3S1U000000igmkUAA',
        'a3S1U000000ifYfUAI',
        'a3S1U000000ifgAUAQ',
        'a3S1U000000ifcAUAQ',
        'a3S1U000000ignOUAQ',
        'a3S1U000000igovUAA',
        'a3S1U000000il27UAA',
        'a3S1U000000ifgTUAQ',
        'a3S1U000000ifYKUAY',
        'a3S1U000000iglIUAQ',
        'a3S1U000000il2qUAA',
        'a3S1U000000igjHUAQ',
        'a3S1U000000igj7UAA',
        'a3S1U000000igjCUAQ',
        'a3S1U000000ifYkUAI',
        'a3S1U000000il2vUAA',
        'a3S1U000000il2lUAA',
        'a3S1U000000ifYRUAY',
        'a3S1U000000ifXYUAY',
        'a3S1U000000ifWZUAY',
        'a3S1U000000il3UUAQ',
        'a3S1U000000ifYIUAY',
        'a3S1U000000il3oUAA',
        'a3S1U000000ifY7UAI',
        'a3S1U000000ifYSUAY',
        'a3S1U000000ifd8UAA',
        'a3S1U000000ifdAUAQ',
        'a3S1U000000ifdDUAQ',
        'a3S1U000000igraUAA',
        'a3S1U000000ifX0UAI',
        'a3S1U000000igobUAA',
        'a3S1U000000ih8ZUAQ',
        'a3S1U000000ih8UUAQ',
        'a3S1U000000ifWrUAI',
        'a3S1U000000ifYgUAI',
        'a3S1U000000ifWXUAY',
        'a3S1U000000ifYVUAY',
        'a3S1U000000il8oUAA',
        'a3S1U000000ifc7UAA',
        'a3S1U000000ifcuUAA',
        'a3S1U000000il98UAA',
        'a3S1U000000ifXXUAY',
        'a3S1U000000il9IUAQ',
        'a3S1U000000igrBUAQ',
        'a3S1U000000ifedUAA',
        'a3S1U000000ifYFUAY',
        'a3S1U000000ifdFUAQ',
        'a3S1U000000ifXoUAI',
        'a3S1U000000il9hUAA',
        'a3S1U000000il9mUAA',
        'a3S1U000000ifXtUAI',
        'a3S1U000000ih7RUAQ',
        'a3S1U000000ifcBUAQ',
        'a3S1U000000ifY5UAI',
        'a3S1U000000ifXlUAI',
        'a3S1U000000ignEUAQ',
        'a3S1U000000igrLUAQ',
        'a3S1U000000ign4UAA',
        'a3S1U000000illbUAA',
        'a3S1U000000illlUAA',
        'a3S1U000000iffkUAA',
        'a3S1U000000illqUAA',
        'a3S1U000000iglmUAA',
        'a3S1U000000ifcIUAQ',
        'a3S1U000000igmLUAQ',
        'a3S1U000000igkeUAA',
        'a3S1U000000ilnNUAQ',
        'a3S1U000000ilnSUAQ',
        'a3S1U000000ilnmUAA',
        'a3S1U000000ilnOUAQ',
        'a3S1U000000igkZUAQ',
        'a3S1U000000ifdUUAQ',
        'a3S1U000000ilnwUAA',
        'a3S1U000000ilo1UAA',
        'a3S1U000000ifYBUAY',
        'a3S1U000000ifYiUAI'
    ) 
    AND Store_Location__c = true
    AND isDeleted = false
    AND Quantity_Needed__c != 0