const textElements = document.querySelectorAll('.grid__item p');
      
      textElements.forEach((p) => {
        let text = p.textContent;
        p.textContent = '';
        text.split('').forEach((letter, index) => {
          setTimeout(() => {
            p.textContent += letter;
          }, 50 * index); // 50ms d'intervalle entre chaque lettre
        });
      });







function afficherExperiences() {
    document.getElementById("competencesSection").classList.add("hidden");
    document.getElementById("experiencesSection").classList.remove("hidden");
}

// Fonction pour masquer la section des expériences et revenir à la section des compétences
function masquerExperiences() {
    document.getElementById("competencesSection").classList.remove("hidden");
    document.getElementById("experiencesSection").classList.add("hidden");
}
