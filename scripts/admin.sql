-- This is our custom sql script for creating a table and inserting values in our DB pre existing tables;

INSERT INTO user ( pseudo, roles, password, email ) VALUES(
    'TonyStark', JSON_ARRAY('ROLE_ADMIN'), SHA2('Tony123!', 256), 'tony.stark@mail.com'
);

CREATE TABLE admincreationMessage ( 
    id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    created_at DATETIME NOT NULL,
    context TEXT NOT NULL
);
INSERT INTO admincreationMessage (created_at, context) VALUES (
    NOW(),
    CONCAT(
        'A logged in user created the following admin profile: ',
        (SELECT user.pseudo FROM user WHERE JSON_CONTAINS(roles, '["ROLE_ADMIN"]') LIMIT 1)
    )
);