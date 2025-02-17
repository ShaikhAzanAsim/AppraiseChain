from flask import Flask, render_template
import mysql.connector
import pandas as pd
import numpy as np
from sklearn.model_selection import train_test_split
from tensorflow.keras.models import Sequential
from tensorflow.keras.layers import Dense
from tensorflow.keras.optimizers import Adam
from tensorflow.keras.models import load_model

app = Flask(__name__)

# Database configuration
db_config = {
    'user': 'root',    
    'password': '', 
    'host': 'localhost',         
    'database': 'appraise_chain', 
}


    
    
@app.route('/')
def show_data():
    # Connect to the database
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor()

    # Example SQL query (adjust the table and column names as per your schema)
    cursor.execute("SELECT * FROM employee")

    # Fetch all the results
    results = cursor.fetchall()

    # Closing the connection
    cursor.close()
    conn.close()
    
# Pass the results to the HTML template
    return render_template('test.html', data=results)

# Route for the second table
@app.route('/second_table')
    

def show_second_table():
    # Connect to the database
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor()

    # Query the required columns from the 'performance' table where Employee_id = 1
    query = """
    SELECT attendance, no_of_fyp, no_of_w, no_of_y, no_of_x, highest_qualification
    FROM performance
    WHERE employee_id = 1
    """
    cursor.execute(query)

    # Fetch the result
    result = cursor.fetchone()

    # Store the values in separate variables
    attendance, no_of_fyp, no_of_w, no_of_y, no_of_x, highest_qualification = result
    
    model = load_model('C:/Users/Hp/21k4500/7thSemester/FYP/model/employee_score_model.h5')
    if highest_qualification.lower() == 'phd':
        h=10
    elif highest_qualification.lower() == 'masters':
        h=5
    elif highest_qualification.lower() == 'bachelors':
        h=2
    
    input_data = np.array([[h, no_of_w, no_of_y, no_of_x, no_of_fyp, attendance]])
    
    predicted_score = model.predict(input_data)
    predicted_score=float(predicted_score)
    employee_id = 1       # Example employee_id (you may want this dynamic too)

        # Define the SQL update query
    update_query = """
    UPDATE performance
    SET ai_score = %s
    WHERE employee_id = %s
    """

    # Execute the update query, passing the variables in the correct order
    cursor.execute(update_query, (predicted_score, employee_id))
    conn.commit()  # Commit the transaction to save the changes
    # Closing the connection
    cursor.close()
    conn.close()

    # Pass the variables to the HTML template
    return render_template('test2.html', 
                           attendance=attendance, 
                           no_of_fyp=no_of_fyp, 
                           no_of_w=no_of_w, 
                           no_of_y=no_of_y, 
                           no_of_x=no_of_x, 
                           highest_qualification=highest_qualification,
                           predicted_score=predicted_score)

if __name__ == '__main__':
    app.run(debug=True)
