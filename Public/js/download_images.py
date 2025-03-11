import requests
import os
import json
import time

# Chemin du dossier local où sauvegarder les images
save_folder = "public/js/images/"

# Créer le dossier s'il n'existe pas
if not os.path.exists(save_folder):
    os.makedirs(save_folder)

# Charger ton image.json
try:
    with open("image.json", "r") as file:
        data = json.load(file)
    print(f"Nombre total d'images à traiter : {len(data['results'])}")
except json.JSONDecodeError as e:
    print(f"Erreur de lecture du JSON : {e}")
    print("Vérifiez que image.json est un fichier JSON valide.")
    exit(1)

# Télécharger chaque image et mettre à jour l'URL
success_count = 0
failure_count = 0
skipped_count = 0

for index, image in enumerate(data["results"]):
    url = image["url"]
    provider = image.get("provider", "unknown")  # Récupérer le provider pour le log

    # Vérifier si l'URL est déjà au format local
    if url.startswith("/js/images/"):
        print(f"Image {index + 1}/{len(data['results'])} déjà traitée (URL locale : {url})")
        skipped_count += 1
        continue

    print(f"Traitement de l'image {index + 1}/{len(data['results'])} (Provider: {provider}, URL: {url})")

    # Extraire le nom du fichier à partir de l'URL
    filename = url.split("/")[-1]
    save_path = os.path.join(save_folder, filename)

    try:
        # Ajouter des en-têtes pour imiter un navigateur
        headers = {
            "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36",
            "Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
            "Accept-Language": "en-US,en;q=0.5",
            "Referer": "https://commons.wikimedia.org/",
            "Connection": "keep-alive"
        }
        response = requests.get(url, stream=True, timeout=10, headers=headers)  # Ajout des headers
        if response.status_code == 200:
            with open(save_path, "wb") as f:
                for chunk in response.iter_content(1024):
                    f.write(chunk)
            print(f"Téléchargé : {filename} (Provider: {provider})")
            # Mettre à jour l'URL dans le JSON
            image["url"] = f"/js/images/{filename}"
            success_count += 1
        else:
            print(f"Échec du téléchargement de {url} (Provider: {provider}, Code HTTP : {response.status_code})")
            failure_count += 1
    except Exception as e:
        print(f"Erreur avec {url} (Provider: {provider}) : {e}")
        failure_count += 1

    time.sleep(2)  # Pause de 2 secondes pour éviter le rate limiting

# Sauvegarder le JSON mis à jour
try:
    with open("image.json", "w") as file:
        json.dump(data, file, indent=4)
    print("Mise à jour de image.json terminée !")
except Exception as e:
    print(f"Erreur lors de la sauvegarde de image.json : {e}")

# Résumé
print(f"\nRésumé :")
print(f"Images traitées avec succès : {success_count}")
print(f"Images échouées : {failure_count}")
print(f"Images déjà traitées (sautées) : {skipped_count}")
print(f"Total des images dans le fichier : {len(data['results'])}")