async function chat() {
  const controller = new AbortController();
  const timeout = setTimeout(() => controller.abort(), 5000); // Timeout de 5 secondes

  try {
    // Charger le fichier JSON local (image.json)
    const response = await fetch("/js/image.json", {
      signal: controller.signal,
      mode: 'cors',
      headers: {
        'Accept': 'application/json',
      },
      cache: 'no-store' // Désactiver le cache
    });

    if (!response.ok) {
      throw new Error(`Network response was not ok: ${response.status} ${response.statusText}`);
    }

    const response2 = await response.json();
    console.log("Données JSON :", response2); // Log pour vérifier le contenu
    console.log("Résultats :", response2.results);

    const chatImage = document.getElementById("chat");
    if (chatImage && response2.results && response2.results.length > 0) {
      // Choisir une image aléatoire parmi toutes
      const randomIndex = Math.floor(Math.random() * response2.results.length);
      console.log("Index aléatoire :", randomIndex);
      const randomImage = response2.results[randomIndex];
      console.log("Image sélectionnée :", randomImage.title || randomImage.url);

      // Appliquer l'image avec un léger délai pour éviter les conflits
      setTimeout(() => {
        chatImage.src = randomImage.url;
        chatImage.style.opacity = 1; // Rendre l'image visible
        chatImage.style.transition = "opacity 0.5s"; // Ajouter une transition fluide
      }, 100);
    } else {
      console.warn("Aucune image trouvée dans la réponse ou réponse invalide.");
    }
  } catch (error) {
    if (error.name === "AbortError") {
      console.warn("La requête a été annulée en raison du délai d'attente.");
    } else {
      console.error("Erreur lors de la récupération de l'image :", error);
    }
  } finally {
    clearTimeout(timeout); // Stop le timeout
  }
}

// Charger une première image au démarrage
document.addEventListener("DOMContentLoaded", function () {
  chat(); // Charger une image initiale
  window.setInterval(chat, 10000); // Rafraîchir toutes les 10 secondes

  let chatImage = document.getElementById("chat");
  if (chatImage) {
    chatImage.style.opacity = 0; // Démarrer avec l'image invisible
  }
});

