from PIL import Image
import os

folder_input = "../assets/original"
folder_output = "../assets/processed"
logo_path = "../assets/logo.png"

os.makedirs(folder_output, exist_ok=True)

ukuran = (800, 600)

for nama_file in os.listdir(folder_input):
    if nama_file.lower().endswith((".jpg", ".jpeg", ".png")):

        path_input = os.path.join(folder_input, nama_file)

        gambar = Image.open(path_input).convert("RGBA")
        gambar.thumbnail(ukuran)

        canvas = Image.new("RGBA", ukuran, "white")

        x = (ukuran[0] - gambar.width) // 2
        y = (ukuran[1] - gambar.height) // 2

        canvas.alpha_composite(gambar, (x, y))

        if os.path.exists(logo_path):
            logo = Image.open(logo_path).convert("RGBA")
            logo.thumbnail((120, 120))

            posisi = (
                ukuran[0] - logo.width - 20,
                ukuran[1] - logo.height - 20
            )

            canvas.alpha_composite(logo, posisi)

        nama_output = os.path.splitext(nama_file)[0] + ".png"
        path_output = os.path.join(folder_output, nama_output)

        canvas.convert("RGB").save(path_output, "PNG")

        print("Berhasil:", nama_output)

print("Semua gambar selesai diproses.")