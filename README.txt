Pet Care Management System (PHP + MySQL)
=========================================

Setup:
1. Install XAMPP (or any Apache + MySQL + PHP stack).
2. Copy this 'petcare' folder into: xampp/htdocs/
3. Start Apache and MySQL from the XAMPP Control Panel.
4. Open http://localhost/phpmyadmin -> Import -> select petcare.sql -> Go.
5. Visit http://localhost/petcare/register.php to create an account.

Files:
- petcare.sql       Database schema + sample doctors/items
- db.php            Database connection
- style.css         Styling
- register.php      User registration (with validation)
- login.php         Login (password_hash based)
- dashboard.php     Doctors list filtered by user location, sorted by rating
- book.php          Book appointment (Create)
- appointments.php  View / Update / Delete appointments
- items.php         Pet items store (Read)
- logout.php        End session

Default location options: Chennai, Bangalore, Mumbai.
