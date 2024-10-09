from flask import Flask, render_template, request, redirect, session
import mysql.connector

app = Flask(__name__)
app.secret_key = 'secret'

# Koneksi ke database
db = mysql.connector.connect(
    host='localhost',
    user='root',
    password='',
    database='sql_injection'
)

# Halaman login
@app.route('/login', methods=['GET', 'POST'])
def login():
    if request.method == 'POST':
        email = request.form['email']
        password = request.form['password']

        # Query rentan SQL Injection
        sql = f"SELECT * FROM users WHERE email = '{email}' AND password = '{password}'"

        cursor = db.cursor(dictionary=True)
        cursor.execute(sql)
        results = cursor.fetchall()

        if results:
            session['loggedin'] = True
            session['success'] = 'Berhasil login ke dalam aplikasi'
            return redirect('/dashboard')
        else:
            session['error'] = 'Email atau password Anda salah'
            return redirect('/login')
        
    error = session.get('error')
    session['error'] = None
    return render_template('login.html', error=error)

# Halaman dashboard
@app.route('/dashboard')
def dashboard():
    if 'loggedin' in session:
        success = session.get('success')
        session['success'] = None
        return render_template('dashboard.html', success=success)
    else:
        return redirect('/login')

if __name__ == '__main__':
    app.run(port=3000)
