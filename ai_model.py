import pandas as pd
import numpy as np
from sklearn.preprocessing import StandardScaler
from sklearn.metrics import mean_absolute_error, mean_squared_error, r2_score
from tensorflow.keras.models import Sequential, load_model
from tensorflow.keras.layers import Dense
from tensorflow.keras.optimizers import Adam
from tensorflow.keras.layers import LeakyReLU
from tensorflow.keras.regularizers import l2
from tensorflow.keras.layers import Dropout
import joblib

# # Load the dataset
# file_path = "researcher_data_final.xlsx"  # Replace with your file path
# df = pd.read_excel(file_path)

# # Select relevant columns
# features = ['total_p', 'citations', 'qualification_score', 'fyp_num', 'fyp_success', 
#             'active', 'w', 'x', 'y', 'attendance_score']
# target = 'target_score'

# X = df[features].values
# y = df[target].values

# # Standardize the features
# scaler = StandardScaler()
# X_scaled = scaler.fit_transform(X)

# # Build the neural network
# model = Sequential([
#     Dense(128, input_dim=len(features), activation='relu'),
#     Dense(64, activation='relu'),
#     Dense(32, activation='relu'),
#     Dense(16, activation='relu'),
#     Dense(1, activation='linear')
# ])

# # Compile the model
# model.compile(optimizer=Adam(learning_rate=0.001), loss='mse', metrics=['mae'])

# # Train the model on the entire dataset
# history = model.fit(X_scaled, y, epochs=100, batch_size=32, verbose=1)

# # Evaluate the model on the training dataset
# predictions = model.predict(X_scaled).flatten()
# mae = mean_absolute_error(y, predictions)
# mse = mean_squared_error(y, predictions)
# r2 = r2_score(y, predictions)

# print("\nModel Evaluation:")
# print(f"Mean Absolute Error (MAE): {mae}")
# print(f"Mean Squared Error (MSE): {mse}")
# print(f"R^2 Score: {r2}")

# # Save the model as "appraisal"
# model.save("appraisal.h5")
# print("Model saved as 'appraisal.h5'.")

# # Save the scaler for consistent input scaling

# scaler_file = "scaler_appraisal.pkl"
# joblib.dump(scaler, scaler_file)
# print(f"Scaler saved as '{scaler_file}'.")

# Test case for validation
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

# Example test case
#['total_p', 'citations', 'qualification_score', 'fyp_num', 'fyp_success','active', 'w', 'x', 'y', 'attendance_score']

sample_data = [5, 5, 10, 5, 10, 10, 20, 15, 10, 10]  # Example input values
predicted_score = test_model(sample_data)
print(f"Predicted Target Score for input {sample_data}: {predicted_score}")
