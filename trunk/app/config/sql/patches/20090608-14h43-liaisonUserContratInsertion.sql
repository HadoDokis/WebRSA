CREATE TABLE users_contratsinsertion (
    user_id             INT NOT NULL REFERENCES users (id),
    contratinsertion_id INT NOT NULL REFERENCES contratsinsertion (id),
    PRIMARY KEY( user_id, contratinsertion_id )
);
