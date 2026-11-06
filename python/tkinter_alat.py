import tkinter as tk
from tkinter import messagebox


def simpan_data():
    kode = entry_kode.get()
    nama = entry_nama.get()
    kategori = entry_kategori.get()
    stok = entry_stok.get()

    if kode == "" or nama == "" or kategori == "" or stok == "":
        messagebox.showwarning(
            "Peringatan",
            "Semua data harus diisi!"
        )
        return

    hasil.insert(
        tk.END,
        f"{kode} - {nama} - {kategori} - Stok: {stok}\n"
    )

    entry_kode.delete(0, tk.END)
    entry_nama.delete(0, tk.END)
    entry_kategori.delete(0, tk.END)
    entry_stok.delete(0, tk.END)

    messagebox.showinfo(
        "Berhasil",
        "Data alat berhasil ditambahkan!"
    )


window = tk.Tk()
window.title("SIPALAB - Input Data Alat")
window.geometry("500x500")


judul = tk.Label(
    window,
    text="INPUT DATA ALAT LABORATORIUM",
    font=("Arial", 16, "bold")
)
judul.pack(pady=20)


tk.Label(window, text="Kode Alat").pack()
entry_kode = tk.Entry(window, width=40)
entry_kode.pack(pady=5)


tk.Label(window, text="Nama Alat").pack()
entry_nama = tk.Entry(window, width=40)
entry_nama.pack(pady=5)


tk.Label(window, text="Kategori").pack()
entry_kategori = tk.Entry(window, width=40)
entry_kategori.pack(pady=5)


tk.Label(window, text="Jumlah Stok").pack()
entry_stok = tk.Entry(window, width=40)
entry_stok.pack(pady=5)


tk.Button(
    window,
    text="Simpan Data",
    command=simpan_data
).pack(pady=15)


hasil = tk.Text(window, width=55, height=10)
hasil.pack(pady=10)


window.mainloop()