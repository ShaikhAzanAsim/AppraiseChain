from flask import Flask, jsonify
from flask_cors import CORS
import mysql.connector
import time
from datetime import datetime, timedelta
from pynput import keyboard, mouse
from plyer import notification
import threading

app = Flask(__name__)
CORS(app)

# Initialize global variables
last_active_time = datetime.now()
active_duration = timedelta(0)
passive_duration = timedelta(0)
is_active = True
notified_inactive = False
notified_active = False
emp_id = None  # Initialize emp_id to None
inactive_threshold = timedelta(seconds=30)

# Database connection
db = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="appraise_chain"
)
cursor = db.cursor()

def get_emp_id():
    """Reads emp_id from emp_id.txt and returns it as a string."""
    try:
        with open("emp_id.txt", "r") as file:
            emp_id_content = file.read().strip()
            return emp_id_content if emp_id_content else None
    except FileNotFoundError:
        return None

def send_notification(title, message):
    """Helper function to send a desktop notification."""
    notification.notify(
        title=title,
        message=message,
        timeout=5
    )

def log_activity():
    """Logs cumulative active and passive durations in the database for the current date and employee."""
    global active_duration, passive_duration, emp_id

    if not emp_id:
        return  # Do not log activity if emp_id is not set

    date_today = datetime.now().date()

    # Check if there is already a row for the current date and employee
    cursor.execute("SELECT id FROM activity WHERE emp_id = %s AND date = %s", (emp_id, date_today))
    result = cursor.fetchone()

    if result:
        # If a row exists, update active and passive times for that row
        cursor.execute("""
            UPDATE activity 
            SET active_time = active_time + %s, 
                passive_time = passive_time + %s 
            WHERE emp_id = %s AND date = %s
        """, (
            active_duration.total_seconds(),
            passive_duration.total_seconds(),
            emp_id,
            date_today
        ))
    else:
        # If no row exists, insert a new row
        cursor.execute("""
            INSERT INTO activity (emp_id, active_time, passive_time, date) 
            VALUES (%s, %s, %s, %s)
        """, (
            emp_id,
            active_duration.total_seconds(), 
            passive_duration.total_seconds(),
            date_today
        ))

    db.commit()

    # Reset accumulated time after logging to avoid double counting
    active_duration = timedelta(0)
    passive_duration = timedelta(0)

def on_activity_detected():
    """Handles user activity and resets the inactivity timer."""
    global last_active_time, is_active, notified_inactive, notified_active
    now = datetime.now()

    # If switching from inactive to active, mark the change
    if not is_active:
        print("User is now active.")
        is_active = True
        notified_inactive = False
        if not notified_active:
            send_notification("Welcome back!", "Happy working!")
            notified_active = True

    # Update last activity time
    last_active_time = now

def check_inactivity():
    """Checks user inactivity, updates activity durations accordingly, and accumulates them."""
    global last_active_time, is_active, active_duration, passive_duration, notified_inactive, notified_active, emp_id

    now = datetime.now()

    # Calculate time since last activity
    time_since_last_active = now - last_active_time

    # Check if user is inactive beyond the threshold
    if time_since_last_active > inactive_threshold:
        if is_active:
            is_active = False
            print("User is now inactive.")
            notified_active = False
            if not notified_inactive:
                send_notification("Inactive Alert", "You've been inactive for a while.")
                notified_inactive = True

    # Accumulate active or passive time based on user state
    if is_active:
        active_duration += timedelta(seconds=1)
    else:
        passive_duration += timedelta(seconds=1)

def activity_tracker():
    """Tracks user activity and logs it periodically."""
    global emp_id
    while True:
        emp_id = get_emp_id()  # Read emp_id from the file
        if emp_id:
            check_inactivity()
            log_activity()
        time.sleep(1)

# Flask route to check status
@app.route("/status", methods=["GET"])
def get_status():
    """Returns the current active/passive durations and employee ID."""
    return jsonify({
        "emp_id": emp_id,
        "active_duration": active_duration.total_seconds(),
        "passive_duration": passive_duration.total_seconds(),
        "is_active": is_active
    })

# Keyboard and mouse event handlers
def on_key_press(key):
    on_activity_detected()

def on_mouse_move(x, y):
    on_activity_detected()

def on_mouse_click(x, y, button, pressed):
    on_activity_detected()

# Start the listeners in a separate thread
def start_listeners():
    keyboard_listener = keyboard.Listener(on_press=on_key_press)
    mouse_listener = mouse.Listener(on_move=on_mouse_move, on_click=on_mouse_click)
    keyboard_listener.start()
    mouse_listener.start()

# Start the activity tracker and listeners
if __name__ == "__main__":
    threading.Thread(target=start_listeners, daemon=True).start()
    threading.Thread(target=activity_tracker, daemon=True).start()
    app.run(host="0.0.0.0", port=5000)
