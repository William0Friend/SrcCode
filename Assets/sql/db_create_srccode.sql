```updated version of the SQL script that adds a `description` column to the `snippets` table:

```sql
CREATE DATABASE myDatabase;

USE myDatabase;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password_hash CHAR(64) NOT NULL,
    type ENUM('developer', 'non-developer') NOT NULL
);

CREATE TABLE snippets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    code TEXT NOT NULL,
    description TEXT NOT NULL,
    action ENUM('buy', 'sell', 'lease', 'trade', 'limbo') NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

