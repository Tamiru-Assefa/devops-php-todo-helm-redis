-- The database (taskdb) are going to be created by the docker 
use taskdb;
CREATE TABLE tasks (
    id INT(11) NOT NULL AUTO_INCREMENT,
    task VARCHAR(255) NOT NULL,
    status ENUM('Under Progress','Completed','Paused') NOT NULL DEFAULT 'Under Progress',
    PRIMARY KEY (id)
);

INSERT INTO tasks (task, status) VALUES 
('Study C#', 'Under Progress'),
('Deploy Docker', 'Paused'),
('Test the Maven Project', 'Completed');