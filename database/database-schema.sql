-- FaCET-RMS Database Schema
-- Create database if it doesn't exist
drop database if exists facet_rms;

CREATE DATABASE IF NOT EXISTS facet_rms;
USE facet_rms;

DROP TABLE IF EXISTS rooms;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS teachers;


CREATE TABLE IF NOT EXISTS rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    capacity INT,
    building VARCHAR(100),
    floor VARCHAR(100),
    status VARCHAR(50),
    startTime TIME,
    endTime TIME,
    amenities JSON
);

CREATE TABLE users (
    -- Primary Key and Identification
    id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    id_number VARCHAR(50) NOT NULL UNIQUE COMMENT 'The student or employee ID number.',

    -- Personal Information
    fullname VARCHAR(255) NOT NULL,
    birthdate DATE NOT NULL,

    -- Contact and Department
    email VARCHAR(255) NOT NULL UNIQUE,
    contact_number VARCHAR(20) NOT NULL,
    department ENUM(
        'BS-Information Technology',
        'BS-Civil Engineering',
        'BS-Math',
        'BITM',
        'Other'
    ) NOT NULL,

    -- Account/Security Details
    password_hash VARCHAR(255) NOT NULL DEFAULT 'User@123', -- In a real application, use a hashed password
    role ENUM('student', 'teacher', 'admin') NOT NULL DEFAULT 'teacher',
    
    -- Status and Profile Picture
    status ENUM('Active', 'Inactive', 'Suspended') NOT NULL DEFAULT 'Active',
    profile_picture LONGBLOB NULL COMMENT 'Stores the binary data of the image.',
    profile_picture_type VARCHAR(50) NULL COMMENT 'e.g., image/jpeg, image/png',

    -- Timestamps
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    -- Define Primary Key
    PRIMARY KEY (id)
);

INSERT INTO users (
    id_number,
    fullname,
    birthdate,
    email,
    contact_number,
    department,
    password_hash,
    role,
    status,
    profile_picture, -- Added
    profile_picture_type -- Added
) VALUES (
    'A-0001',
    'FaCET Administrator',
    '1980-01-01',
    'admin@dorsu.edu.ph',
    '09123456789',
    'BS-Information Technology',
    '$2y$12$pgscMmdRr4scEi5jmKu.HeDPeNo4dXn0wll8pOtiGYcMoTKGF.Sv.', -- In a real application, use a hashed password
    'admin',
    'Active',
    NULL, -- Added
    NULL  -- Added
);

