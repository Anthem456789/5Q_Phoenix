import os
import subprocess

def main():
    # Percorso completo dell'eseguibile PHP (modifica questo con il tuo percorso reale)
    php_path = r"C:\\xampp\\php\\php.exe"  # Adatta questo percorso al tuo sistema

    # Ottieni il percorso della directory corrente
    current_directory = os.path.dirname(os.path.abspath(__file__))

    # Percorso completo del file PHP
    php_file = os.path.join(current_directory, "SetUpDb.php")  # Costruisci il percorso completo

    # Verifica se il file PHP esiste
    if not os.path.exists(php_file):
        print(f"Il file {php_file} non esiste. Verifica il percorso.")
        return  # Esci dalla funzione se il file non esiste

    # Verifica se l'eseguibile PHP esiste
    if not os.path.exists(php_path):
        print(f"L'eseguibile PHP {php_path} non esiste. Verifica il percorso.")
        return  # Esci dalla funzione se l'eseguibile non esiste

    # Esegui il comando PHP tramite subprocess
    try:
        result = subprocess.run([php_path, php_file], check=True, text=True, capture_output=True)
        # Mostra l'output del comando PHP
        print("Output:\n", result.stdout)
        if result.stderr:
            print("Errori:\n", result.stderr)
    except subprocess.CalledProcessError as e:
        print(f"Si è verificato un errore durante l'esecuzione del file PHP: {e}")
        print("Output parziale:\n", e.stdout)
        print("Errori:\n", e.stderr)
    except FileNotFoundError as e:
        print(f"File non trovato: {e}")
    except Exception as e:
        print(f"Si è verificato un errore imprevisto: {e}")

if __name__ == "__main__":
    main()