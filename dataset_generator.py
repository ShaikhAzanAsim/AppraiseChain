import pandas as pd

# Load the Excel file
file_path = "researcher_data.xlsx"  # Replace with your actual file path
df = pd.read_excel(file_path)

# Create the 'total_p' variable
def calculate_total_p(total_paper):
    if total_paper == 0:
        return 0
    elif 1 <= total_paper <= 10:
        return 2
    elif 11 <= total_paper <= 20:
        return 4
    else:
        return 5
    return 0

df['total_p'] = df['total_paper'].apply(calculate_total_p)

# Create the 'citations' variable
def calculate_citations(total_citation):
    if 0 <= total_citation <= 50:
        return 1
    elif 51 <= total_citation <= 250:
        return 2
    elif 251 <= total_citation <= 500:
        return 3
    elif 501 <= total_citation <= 700:
        return 4
    elif total_citation > 700:
        return 5
    return 0

df['citations'] = df['total_citation'].apply(calculate_citations)

# Create the 'qualification_score' variable
def calculate_qualification(qualification):
    if qualification == 'Bachelors':
        return 4
    elif qualification == 'Masters':
        return 7
    elif qualification == 'PhD':
        return 10
    return 0

df['qualification_score'] = df['qualification'].apply(calculate_qualification)

# Create the 'fyp_num' variable
def calculate_fyp_num(total_fyp):
    if total_fyp == 0:
        return 0
    elif 1 <= total_fyp <= 3:
        return 1
    elif 4 <= total_fyp <= 5:
        return 2
    elif 6 <= total_fyp <= 8:
        return 3
    elif 9 <= total_fyp <= 10:
        return 4
    else:
        return 5

df['fyp_num'] = df['total_fyp'].apply(calculate_fyp_num)

# Create the 'fyp_success' variable
df['fyp_success'] = df['percentage_success_fyp'] * 10

# Create the 'active' variable
df['active'] = df['percentage_active_time'] * 10

# Create variables 'w', 'x', 'y'
df['w'] = df['percentage_w'] * 20
df['x'] = df['percentage_x'] * 15
df['y'] = df['percentage_y'] * 10


# Create the 'attendance_score' variable
df['attendance_score'] = df['attendance'] * 10


# Calculate the 'target_score' variable
df['target_score'] = (
    df['total_p'] +
    df['citations'] +
    df['qualification_score'] +
    df['fyp_num'] +
    df['fyp_success'] +
    df['active'] +
    df['w'] +
    df['x'] +
    df['y'] +
    df['attendance_score']
)

# Save the updated DataFrame to a new Excel file
output_file_path = "updated_researcher_data.xlsx"
df.to_excel(output_file_path, index=False)

print(f"Data processed successfully. Output saved to {output_file_path}.")
