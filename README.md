# Overview

This project is for personal interest to try out Laravel 12.
It's like an experimental playground, so there's no design consistency between screens, and some features are still under construction.

It's not a project with a clear purpose, so feel free to check out the contents, try it out, and play around with it by making changes.

I considered myself an expert up to Laravel 9, but after touching Laravel again after a few years, I feel like there have been a lot of surprising changes.

# Setup

## Developer Environment

I used a MACOS M1.
Some adjustments may be necessary if you want to run it on Windows or other environments.

I installed Docker version 27.4.0 for debugging.

# How to Start

Basically, all servers are started by docker compose.
You may need to change the permissions of `/src/storage`.

## Normal startup

    bash ./bootup.bash

## Cleanup including DB

    bash ./clear-reload-laravel.bash

## Laravel cache clean and dump autoload

    bash ./reload-laravel.bash

# How to Use

## Login

    Access http://localhost:8080/login

## Kanban Task Board

    Access http://localhost:8080/kanban

You can create, edit, and move the status of tickets by dragging and dropping.

## Admin User Management

    Access http://localhost:8080/admin/users

This feature is under construction.
I might complete it when I have time.
In addition to being able to edit and delete all tasks in kanban, you will also be able to register and delete users.

I might also have a feature to review the kanban that a particular user should be seeing.

As this feature nears completion, collaboration between multiple users may be possible, but that's up to me.

## Debugging Information

The VSCode debug settings are also committed as is, so it is easy to debug as is.

phpmyadmin : https://localhost:8088
phpmyadmin is also automatically set up, so use it if you want to check the contents of the DB.

# Things I want to try in the future

- DDD design pattern or another design pattern
- Collaboration of multiple users
- Real-time synchronization during collaboration
- Comment function for tasks
- Mention function to specific users when commenting on tasks
- Read management of mentions and messages
