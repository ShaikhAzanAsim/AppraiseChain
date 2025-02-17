
import mysql.connector
from tensorflow.keras.models import load_model
import joblib
from plyer import notification

# Database configuration
db_config = {
    'user': 'root',    
    'password': '', 
    'host': 'localhost',         
    'database': 'appraise_chain', 
}

# Define the file path
file_path = 'kpi.txt'

try:
    # Read the file content
    with open(file_path, 'r') as file:
        lines = file.readlines()

    # Remove newline characters and convert to integers
    lines = [int(line.strip()) for line in lines if line.strip()]

    # Ensure the file contains exactly 7 lines
    if len(lines) != 6:
        raise ValueError(f"Invalid file format: Expected 7 lines, found {len(lines)}")

    # Assign values to meaningful variables
    attendance_weight = lines[0]
    fyp_success_weight = lines[1]
    w_paper_weight = lines[2]
    y_paper_weight = lines[3]
    x_paper_weight = lines[4]
    activity_weight = lines[5]

    # Output the variables for debugging
    print(f"Attendance Weight: {attendance_weight}")
    print(f"FYP Success Weight: {fyp_success_weight}")
    print(f"W Paper Weight: {w_paper_weight}")
    print(f"Y Paper Weight: {y_paper_weight}")
    print(f"X Paper Weight: {x_paper_weight}")
    print(f"Activity Weight: {activity_weight}")

except FileNotFoundError:
    print(f"Error: File not found at {file_path}")
except ValueError as e:
    print(f"Error: {e}")
except Exception as e:
    print(f"An unexpected error occurred: {e}")

def send_notification(title, message):
    """Helper function to send a desktop notification."""
    notification.notify(
        title=title,
        message=message,
        timeout=5
    )

def read_emp_cnic():
    with open('hod_id.txt', 'r') as file:
        h_cnic = file.read(15).strip()
    return h_cnic


def get_all_paper(h_cnic):
    # Connect to the database
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)
    
    # Query for employee name
    query = "select count(r.id) as all_paper from researchpaper r join hod h on h.organization_id= r.organization_id where h.cnic = %s "
    cursor.execute(query, (h_cnic,))
    result = cursor.fetchone()
    
    cursor.close()
    conn.close()
    return result

def get_all_info(h_cnic,total_days):
    # Connect to the database
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)

    # Updated query
    query = """
        SELECT 
    CONCAT(e.fname, ' ', e.lname) AS employee_name,
    e.cnic AS cnic,
    
    (CASE 
        WHEN (SELECT COUNT(*) FROM researchpaper r WHERE r.employee_id = e.cnic) = 0 THEN 0
        WHEN (SELECT COUNT(*) FROM researchpaper r WHERE r.employee_id = e.cnic) BETWEEN 1 AND 10 THEN 2
        WHEN (SELECT COUNT(*) FROM researchpaper r WHERE r.employee_id = e.cnic) BETWEEN 11 AND 20 THEN 4
        ELSE 5
    END) AS total_p,
    (CASE
     	WHEN (SELECT SUM(r.citations_count) FROM researchpaper r WHERE r.employee_id = e.cnic) BETWEEN 0 AND 5 THEN 0.5
        WHEN (SELECT SUM(r.citations_count) FROM researchpaper r WHERE r.employee_id = e.cnic) BETWEEN 6 AND 50 THEN 1
        WHEN (SELECT SUM(r.citations_count) FROM researchpaper r WHERE r.employee_id = e.cnic) BETWEEN 51 AND 250 THEN 2
        WHEN (SELECT SUM(r.citations_count) FROM researchpaper r WHERE r.employee_id = e.cnic) BETWEEN 251 AND 500 THEN 3
        WHEN (SELECT SUM(r.citations_count) FROM researchpaper r WHERE r.employee_id = e.cnic) BETWEEN 501 AND 700 THEN 4
        WHEN (SELECT SUM(r.citations_count) FROM researchpaper r WHERE r.employee_id = e.cnic) > 700 THEN 5
        ELSE 0
    END) AS citations,
    
    (CASE 
        WHEN p.qualification = 'PhD' THEN 10
        WHEN p.qualification = 'Masters' THEN 7
        WHEN p.qualification = 'Bachelors' THEN 4
        ELSE 0 
    END) AS qualification,

    (CASE 
        WHEN (SELECT COUNT(*) FROM fyp f WHERE f.emp_id = e.cnic) = 0 THEN 0
        WHEN (SELECT COUNT(*) FROM fyp f WHERE f.emp_id = e.cnic) BETWEEN 1 AND 3 THEN 1
        WHEN (SELECT COUNT(*) FROM fyp f WHERE f.emp_id = e.cnic) BETWEEN 4 AND 5 THEN 2
        WHEN (SELECT COUNT(*) FROM fyp f WHERE f.emp_id = e.cnic) BETWEEN 6 AND 8 THEN 3
        WHEN (SELECT COUNT(*) FROM fyp f WHERE f.emp_id = e.cnic) BETWEEN 9 AND 10 THEN 4
        ELSE 5
    END) AS fyp_num,
    
    (CASE 
        WHEN (SELECT COUNT(*) FROM fyp f WHERE f.emp_id = e.cnic) > 0 THEN
            ((SELECT COUNT(*) FROM fyp f WHERE f.emp_id = e.cnic AND f.status = 1) * %s) / 
            (SELECT COUNT(*) FROM fyp f WHERE f.emp_id = e.cnic)
        ELSE 0
    END) AS fyp_success,
    
    -- Active Time Score
    (CASE 
        WHEN ((SELECT SUM(t.active_time) FROM activity t WHERE t.emp_id = e.cnic) + 
              (SELECT SUM(t.passive_time) FROM activity t WHERE t.emp_id = e.cnic)) > 0 THEN
            (SELECT SUM(t.active_time) FROM activity t WHERE t.emp_id = e.cnic) * %s /
            ((SELECT SUM(t.active_time) FROM activity t WHERE t.emp_id = e.cnic) +
             (SELECT SUM(t.passive_time) FROM activity t WHERE t.emp_id = e.cnic))
        ELSE 0
    END) AS active,

    (CASE 
        WHEN (SELECT COUNT(*) FROM researchpaper r WHERE r.organization_id = h.organization_id AND r.hrjs_category = 'W') > 0 		  THEN
            ((SELECT COUNT(*) FROM researchpaper r WHERE r.employee_id = e.cnic AND r.hrjs_category = 'W') * %s) /
            (SELECT COUNT(*) FROM researchpaper r WHERE r.organization_id = h.organization_id AND r.hrjs_category = 'W')
        ELSE 0
     END) AS w,

    (CASE 
        WHEN (SELECT COUNT(*) FROM researchpaper r WHERE r.organization_id = h.organization_id AND r.hrjs_category = 'X') > 0 		  THEN
            ((SELECT COUNT(*) FROM researchpaper r WHERE r.employee_id = e.cnic AND r.hrjs_category = 'X') * %s) /
            (SELECT COUNT(*) FROM researchpaper r WHERE r.organization_id = h.organization_id AND r.hrjs_category = 'X')
        ELSE 0
     END) AS x,
    
    (CASE 
        WHEN (SELECT COUNT(*) FROM researchpaper r WHERE r.organization_id = h.organization_id AND r.hrjs_category = 'Y') > 0 	      THEN
            ((SELECT COUNT(*) FROM researchpaper r WHERE r.employee_id = e.cnic AND r.hrjs_category = 'Y') * %s) /
            (SELECT COUNT(*) FROM researchpaper r WHERE r.organization_id = h.organization_id AND r.hrjs_category = 'Y')
        ELSE 0
     END) AS y,
    
    ((SELECT COUNT(a.date) FROM attendance a WHERE a.emp_id = e.cnic AND a.status = 1) * %s / %s ) AS attendance_score
    

    FROM 
        employee e
    JOIN 
        hod h ON e.organization_id = h.organization_id
    JOIN
        performance p ON p.emp_id = e.cnic  
    WHERE 
        h.cnic = %s ;
        """

    cursor.execute(query, (fyp_success_weight,activity_weight,w_paper_weight,x_paper_weight,y_paper_weight,attendance_weight,total_days,h_cnic))
    result = cursor.fetchall()
    
    cursor.close()
    conn.close()
    return result



h_cnic=read_emp_cnic()


def test_model(test_data, model_path="appraisal.h5", scaler_path="scaler_appraisal.pkl"):
    """
    Test the saved model with new data.
    test_data: A list or array of shape (len(features),).
    model_path: Path to the saved model file.
    scaler_path: Path to the saved scaler file.
    """
    # Load the saved scaler and model
    loaded_scaler = joblib.load(scaler_path)
    loaded_model = load_model(model_path)
    
    # Scale the input data
    test_data_scaled = loaded_scaler.transform([test_data])
    
    # Predict using the saved model
    predicted_score = loaded_model.predict(test_data_scaled)[0][0]
    return predicted_score

def update_ai_score(cnic, ai_score):
    # Connect to the database
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor()
    
    try:
        # Convert the ai_score to a regular float (which is compatible with MySQL)
        ai_score = float(ai_score)
        
        # Update query
        query = """
        UPDATE performance
        SET ai_score = %s
        WHERE emp_id = %s
        """
        
        # Execute the update query
        cursor.execute(query, (ai_score, cnic))
        conn.commit()
        print(f"AI score updated for employee with CNIC: {cnic}")
        
    except mysql.connector.Error as err:
        print(f"Error updating ai_score for CNIC: {cnic}: {err}")
    finally:
        cursor.close()
        conn.close()

def predict_score(h_cnic, total_days):
    # Step 1: Fetch data from the database
    data = get_all_info(h_cnic, total_days)
    
    # Check if data is returned
    if not data:
        print("No data found for the provided HOD CNIC.")
        return None
    
    # Assuming the query returns one row per employee
    predictions = []
    
    for row in data:
        # Extract the relevant fields for the model
        test_data = [
            row['total_p'],  # Total papers
            row['citations'],  # Total citations
            row['qualification'],  # Qualification score
            row['fyp_num'],  # FYP number
            row['fyp_success'],  # FYP success score
            row['active'],  # Active time score
            row['w'],  # W category score
            row['x'],  # X category score
            row['y'],  # Y category score
            row['attendance_score']  # Attendance score
        ]
        
        # Handle missing or None values in the data (replace with 0 or suitable default)
        test_data = [value if value is not None else 0 for value in test_data]
        
        # Step 2: Predict using the AI model
        try:
            predicted_score = test_model(test_data)
        except Exception as e:
            print(f"Error in prediction for {row['employee_name']} (CNIC: {row['cnic']}): {e}")
            continue
        
        # Append result
        predictions.append({
            'employee_name': row['employee_name'],
            'cnic': row['cnic'],
            'predicted_score': predicted_score
        })
        
        # Update the ai_score in the database for this employee
        update_ai_score(row['cnic'], predicted_score)
        
    # Find the employee with the highest predicted score
    if predictions:
        top_employee = max(predictions, key=lambda x: x['predicted_score'])
        send_notification(
            title="Top Performer Alert 🥳",
            message=f"Employee {top_employee['employee_name']} "
                    f"has the highest AI score: {top_employee['predicted_score']:.2f}"
        )

    return predictions

    
    

# Example usage
total_days = 20  # Example total days
predictions = predict_score(h_cnic, total_days)

# Display predictions
if predictions:
    for result in predictions:
        print(f"Employee: {result['employee_name']} (CNIC: {result['cnic']}), Predicted Score: {result['predicted_score']:.2f}")
        
else:
    print("No predictions were generated.")