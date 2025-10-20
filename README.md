<p align="center">
<img src="public/assets/images/Logo.svg" width="150">
</p>

## About App

A comprehensive and automated scheduling platform designed to optimize the allocation of laboratory resources, assistants, and practicum sessions within the Informatics Department Laboratory.

### Our Feature

- Automated Scheduling Algorithm: Generates weekly/monthly schedules based on assistant preferences and availability constraints.

- Role-Based Access Control (RBAC): Separates permissions for Admins (Laboran) and Assistants.

- Comprehensive Assistant Management: Includes profiles, work history, and leave tracking.

## Installation Locally

- clone repository `git clone git@github.com:Taufikrw/sistem-penjadwalan-labjif.git` and `cd sistem-penjadwala-labjif`
- install dependency `composer install` and `npm install`
- copy environtment `cp .env.example .env`
- configuration environtment variable
- generate key `php artisan key:generate`
- migrate db `php artisan migrate`
- run `composer run dev`
