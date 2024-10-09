import express from 'express';
import mysql from 'mysql2';
import session from 'express-session';
import bodyParser from 'body-parser';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const app = express();

// Koneksi ke database
const db = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: '',
    database: 'sql_injection'
});

db.connect((error) => {
    if (error) throw error;
    console.log('Terhubung dengan database');
});

// Middleware
app.use(bodyParser.urlencoded({ extended: true }));
app.use(express.static(path.join(__dirname, 'public')));
app.use(session({
    secret: 'secret',
    resave: false,
    saveUninitialized: true
}));

// Set view engine
app.set('view engine', 'ejs');

// Halaman login
app.get('/login', (req, res) => {
    const error = req.session.error;
    req.session.error = null; // Reset error setelah ditampilkan
    res.render('login', { error });
});

// Proses otentikasi
app.post('/auth', (req, res) => {
    const email = req.body.email;
    const password = req.body.password;

    // Menggunakan prepared statements untuk menghindari SQL Injection
    const sql = 'SELECT * FROM users WHERE email = ? AND password = ?';
    
    db.query(sql, [email, password], (err, results) => {
        if (err) throw err;
        if (results.length > 0) {
            req.session.loggedin = true; // Set session login
            req.session.success = 'Berhasil login ke dalam aplikasi'; // Set flash message
            res.redirect('/dashboard');
        } else {
            req.session.error = 'Email atau password Anda salah';
            res.redirect('/login');
        }
    });
});

// Halaman dashboard
app.get('/dashboard', (req, res) => {
    console.log('Mengakses /dashboard');
    if (req.session.loggedin) {
        const success = req.session.success; // Ambil pesan sukses
        req.session.success = null; // Reset pesan setelah ditampilkan
        res.render('dashboard', { success }); // Kirim pesan ke view
    } else {
        console.log('Pengguna tidak terautentikasi, redirect ke /login');
        res.redirect('/login');
    }
});

// Menjalankan server
app.listen(3000, () => {
    console.log('Server berjalan di http://localhost:3000');
});
