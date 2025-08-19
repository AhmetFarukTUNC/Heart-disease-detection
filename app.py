from flask import Flask, request, jsonify
import numpy as np
import joblib
import os
from sklearn.preprocessing import LabelEncoder
from flask_cors import CORS  # CORS için

app = Flask(__name__)
CORS(app)  # Herhangi bir origin'e izin verir

MODEL_PATH = "random_forest_model.pkl"
ENCODERS_PATH = "encoders.pkl"

# Model ve encoderları yükle
if not os.path.exists(MODEL_PATH) or not os.path.exists(ENCODERS_PATH):
    raise FileNotFoundError("Model veya encoder dosyası bulunamadı. Önce modeli ve encoderları oluşturun.")

model = joblib.load(MODEL_PATH)
encoders = joblib.load(ENCODERS_PATH)
print("✅ Model ve encoderlar yüklendi.")

# Kategorik sütun sıralaması
categorical_columns = ["Sex", "ChestPainType", "RestingECG", "ExerciseAngina", "ST_Slope"]

# Tüm sütun sıralaması
all_columns = ["Age","Sex","ChestPainType","RestingBP","Cholesterol",
               "FastingBS","RestingECG","MaxHR","ExerciseAngina","Oldpeak","ST_Slope"]

@app.route("/predict", methods=["POST"])
def predict():
    try:
        data = request.get_json()
        features = data.get("features")
        if not features or len(features) != len(all_columns):
            return jsonify({"error": f"Özellik sayısı yanlış. {len(all_columns)} özellik bekleniyor."}), 400

        processed_features = []
        for i, col in enumerate(all_columns):
            value = features[i]
            if col in categorical_columns:
                # Eğer string ise encoder ile sayıya çevir
                if isinstance(value, str):
                    value = encoders[col].transform([value])[0]
            # Sayısal değerleri float veya int olarak al
            processed_features.append(value)

        features_array = np.array(processed_features).reshape(1, -1)
        prediction = model.predict(features_array)

        return jsonify({"prediction": int(prediction[0])})
    except Exception as e:
        return jsonify({"error": str(e)}), 400

if __name__ == "__main__":
    app.run(debug=True)
