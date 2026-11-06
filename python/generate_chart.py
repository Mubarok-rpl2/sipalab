import mysql.connector
import matplotlib.pyplot as plt
import os

# Koneksi ke database
db = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="sipalab"
)

cursor = db.cursor()

# Mengambil jumlah alat berdasarkan kondisi
cursor.execute("""
    SELECT kondisi, SUM(jumlah_stok)
    FROM alat
    GROUP BY kondisi
""")

data = cursor.fetchall()

kondisi = []
jumlah = []

for row in data:
    kondisi.append(row[0])
    jumlah.append(row[1])

# Membuat grafik
plt.figure(figsize=(7, 4))

plt.bar(kondisi, jumlah)

plt.title("Kondisi Alat Laboratorium")
plt.xlabel("Kondisi")
plt.ylabel("Jumlah Alat")

plt.tight_layout()

# Folder hasil grafik
folder_output = "../assets/processed"
os.makedirs(folder_output, exist_ok=True)

# Simpan grafik
path_output = os.path.join(
    folder_output,
    "grafik_kondisi_alat.png"
)

plt.savefig(path_output)
plt.close()

print("Grafik berhasil dibuat:")
print(path_output)

cursor.close()
db.close()