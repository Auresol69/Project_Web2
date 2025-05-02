-- SQL script to update customer table with province_id, district_id, and address_detail

UPDATE customer SET 
    province_id = '01',
    district_id = '001',
    address_detail = '123 Đường Lê Lợi'
WHERE macustomer = 'CUS001';

UPDATE customer SET 
    province_id = '01',
    district_id = '002',
    address_detail = '456 Đường Nguyễn Huệ'
WHERE macustomer = 'CUS002';

-- Add more updates as needed for other customers
