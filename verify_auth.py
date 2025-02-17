from flask import Flask, request, jsonify
import requests
from flask_cors import CORS
import mysql.connector

app = Flask(__name__)
CORS(app)

# Database configuration
db_config = {
    'user': 'root',    
    'password': '', 
    'host': 'localhost',         
    'database': 'appraise_chain', 
}

def read_emp_cnic():
    with open('emp_id.txt', 'r') as file:
        e_cnic = file.read(15).strip()
    return e_cnic

def get_employee_name(e_cnic):
    # Connect to the database
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor(dictionary=True)
    
    # Query for employee name
    query = "SELECT fname, lname FROM employee WHERE cnic = %s"
    cursor.execute(query, (e_cnic,))
    result = cursor.fetchone()
    
    cursor.close()
    conn.close()
    return result

def get_paper_info(doi=None, title=None, journal_name=None):
    if doi:
        url = f"https://api.crossref.org/works/{doi}"
        try:
            response = requests.get(url)
            response.raise_for_status()
            paper = response.json().get("message", {})
            return format_paper_info(paper)
        except requests.exceptions.RequestException as err:
            return {"error": str(err)}
    elif title:
        url = "https://api.crossref.org/works"
        params = {
            "query.title": title,
            "query.container-title": journal_name,
            "rows": 1
        }
        try:
            response = requests.get(url, params=params)
            response.raise_for_status()
            data = response.json().get("message", {}).get("items", [])
            if data:
                return format_paper_info(data[0])
            else:
                return {"error": "No results found for the specified title and journal."}
        except requests.exceptions.RequestException as err:
            return {"error": str(err)}
    else:
        return {"error": "Please provide either a DOI or a title."}

def format_paper_info(paper):
    return {
        "DOI": paper.get("DOI", "No DOI found"),
        "Title": paper.get("title", ["No title found"])[0],
        "Journal": paper.get("container-title", ["No journal found"])[0],
        "Publication Date": paper.get("published-print", {}).get("date-parts", [[None]])[0][0],
        "Citations": paper.get("is-referenced-by-count", 0),
        "Volume": paper.get("volume", "N/A"),
        "Issue": paper.get("issue", "N/A"),
        "ISSN": paper.get("ISSN", ["No ISSN found"])[0],
        "Authors": [
            f"{author.get('given', '')} {author.get('family', '')}".strip() 
            for author in paper.get("author", [])
        ]
    }

@app.route('/get-paper-info', methods=['GET'])
def api_get_paper_info():
    doi = request.args.get('doi')
    title = request.args.get('title')
    journal_name = request.args.get('journal_name')
    
    # Fetch paper information
    paper_info = get_paper_info(doi=doi, title=title, journal_name=journal_name)
    
    if "error" in paper_info:
        return jsonify(paper_info)
    
    # Fetch employee details
    e_cnic = read_emp_cnic()
    employee = get_employee_name(e_cnic)
    
    if not employee:
        return jsonify({"error": "Employee not found"})
    
    # Check if employee name is in authors list
    full_name = f"{employee['fname']} {employee['lname']}"
    authors_list = paper_info.get("Authors", [])
    
    if full_name in authors_list:
        # Send paper info to frontend if author matches
        print(authors_list)
        return jsonify(paper_info)
    else:
        # Send signal if author does not match
        print(authors_list)
        return jsonify({"error": "Please enter a research paper authored by you."})
    
    
    
    
    
@app.route('/evaluate', methods=['POST'])
def evaluate():
    # Perform evaluation logic here
    # Example: process data, update the database, etc.
    print("Evaluation logic executed")
    return jsonify({'status': 'success', 'message': 'Evaluation completed successfully'})

if __name__ == '__main__':
    app.run(debug=True)
