import random

# 1. Création de la matrice du labyrinthe

def create_maze(size):
    maze = [[1 for _ in range(size)] for _ in range(size)]
    maze[0][0] = 0  # Point de départ
    num_total = size * size
    chemins60 = int(num_total * 0.65)
    chemin_départ = 1

    while chemin_départ < chemins60:
        x = random.randint(0, size - 1)
        y = random.randint(0, size - 1)
        if maze[x][y] == 1:
            maze[x][y] = 0
            chemin_départ += 1

    sortie = [(size - 1, i) for i in range(size)]
    sortie_x, sortie_y = random.choice(sortie)
    maze[sortie_x][sortie_y] = 2
    return maze

# 2. Affichage de la matrice

def draw_maze(maze, dico):
    for raw in maze:
        ligne = "".join(dico[value] for value in raw)
        print(ligne)

# 3. Déplacement du joueur

def create_perso(start):
    return {"char": "@", "x": start[0], "y": start[1], "score": 0}

def update_p(maze, letter, p):
    if letter == "z" and p["x"] > 0 and maze[p["x"] - 1][p["y"]] != 1:
        p["x"] -= 1
    elif letter == "s" and p["x"] < len(maze) - 1 and maze[p["x"] + 1][p["y"]] != 1:
        p["x"] += 1
    elif letter == "q" and p["y"] > 0 and maze[p["x"]][p["y"] - 1] != 1:
        p["y"] -= 1
    elif letter == "d" and p["y"] < len(maze[0]) - 1 and maze[p["x"]][p["y"] + 1] != 1:
        p["y"] += 1

def enfin_sortie(p, maze):
    x, y = p["x"], p["y"]
    return x == len(maze) - 1 and maze[x][y] == 2

# 4. Création des objets
def create_items(maze, num_items):
    o = {}
    while len(o) < num_items:
        x = random.randint(0, len(maze) - 1)
        y = random.randint(0, len(maze[0]) - 1)
        if maze[x][y] != 1 and (x, y) not in o and maze[x][y] != 2:
            o[x] = y
    return o

def update_objects(p, o):
    keys_to_remove = []
    for x, y in o.items():
        if p["x"] == x and p["y"] == y:
            p["score"] += 1
            keys_to_remove.append(x)
    for x in keys_to_remove:
        del o[x]

# 5. Gestion du Minotaure
def create_minotaure(maze):
    while True:
        x = random.randint(0, len(maze) - 1)
        y = random.randint(0, len(maze[0]) - 1)
        if maze[x][y] == 0:
            return {"char": "M", "x": x, "y": y}

def place_minotaure(maze, joueur):
    size = len(maze)
    while True:
        x = random.randint(0, size - 1)
        y = random.randint(0, size - 1)
        # Vérifie que la position est loin du joueur et n'est pas un mur
        if maze[x][y] != 1 and abs(joueur["x"] - x) + abs(joueur["y"] - y) > 5:
            return {"x": x, "y": y, "char": "M","move_delay":2}

def minotaure_collision(p, mino):
    return p["x"] == mino["x"] and p["y"] == mino["y"]

def move_minotaure(maze, mino, joueur):
    if mino["move_delay"] > 0:
        mino["move_delay"] -= 1
        return  # Le Minotaure reste immobile ce tour-ci
    mino["move_delay"] = 1  # Réinitialisation du compteur après un mouvement
    # Calcul des déplacements possibles
    dx = joueur["x"] - mino["x"]
    dy = joueur["y"] - mino["y"]
    
    possible_moves = []
    if dx != 0:  # Mouvement vertical
        possible_moves.append((mino["x"] + (1 if dx > 0 else -1), mino["y"]))
    if dy != 0:  # Mouvement horizontal
        possible_moves.append((mino["x"], mino["y"] + (1 if dy > 0 else -1)))

    # Filtrage des mouvements valides (dans le labyrinthe et pas un mur)
    valid_moves = [
        (nx, ny) for nx, ny in possible_moves
        if 0 <= nx < len(maze) and 0 <= ny < len(maze[0]) and maze[nx][ny] != 1
    ]

    if valid_moves:  # Si des mouvements valides existent
        next_move = random.choice(valid_moves)
        mino["x"], mino["y"] = next_move
    else:  # Coincé, le Minotaure casse un mur
        if possible_moves:
            nx, ny = possible_moves[0]  # Choisit le premier mur à casser
            if 0 <= nx < len(maze) and 0 <= ny < len(maze[0]) and maze[nx][ny] == 1:
                maze[nx][ny] = 0  # Le mur est détruit

# 6. Affichage du labyrinthe avec le Minotaure
def draw_maze_with_char_and_items(maze, dico, p, o, mino):
    temp_maze = [[dico[cell] for cell in row] for row in maze]
    temp_maze[p["x"]][p["y"]] = p["char"]
    temp_maze[mino["x"]][mino["y"]] = mino["char"]
    for x, y in o.items():
        if temp_maze[x][y] == " ":
            temp_maze[x][y] = "."
    for row in temp_maze:
        print("".join(row))
    print("artefacts rammasés :", p["score"])
    
def collect_items(perso,items):
    if (perso["x"],perso["y"]) in items:
        items.remove((perso["x"],perso["y"]))
        perso["score"]+=1

#Fonction fil d'arriane
def fil_d_ariane(maze, joueur, items):
    """
    Vérifie si le joueur peut atteindre la sortie et tous les objets (items).
    Si ce n'est pas possible, retourne False pour régénérer le labyrinthe.
    """
    def dfs(x, y, visited):
        """Explore les cases accessibles à partir de (x, y)."""
        if (x, y) in visited:
            return
        visited.add((x, y))

        for dx, dy in [(-1, 0), (1, 0), (0, -1), (0, 1)]:  # Déplacements possibles
            nx, ny = x + dx, y + dy
            if 0 <= nx < len(maze) and 0 <= ny < len(maze[0]) and maze[nx][ny] != 1:
                dfs(nx, ny, visited)

    # Initialiser la recherche à partir de la position du joueur
    visited = set()
    dfs(joueur["x"],joueur["y"], visited)

    # Identifier la sortie
    sortie = [(x, y) for x in range(len(maze)) for y in range(len(maze[0])) if maze[x][y] == 2]
    if not sortie:
        return False  # Pas de sortie trouvée dans le labyrinthe

    # Construire la liste des cibles à atteindre : sortie + positions des objets
    targets = sortie + [(x, y) for x, y in items.items()]

    # Vérifier si toutes les cibles sont atteignables
    return all(target in visited for target in targets)




# 7. Énigmes du Sphinx

def pose_enigme():
    enigmes = [
        ("Je suis toujours devant vous, mais vous ne pouvez jamais m’atteindre. Qui suis-je ?", ["l'avenir", "le futur", "futur","avenir"]),
        ("Quel est le nombre qui, multiplié par lui-même, donne 25 ?", ["5","le 5"]),
        ("Je peux être brisé sans être touché. Qui suis-je ?", ["le silence", "un silence", "silence"]),
        ("Lors d'une course de vélo, un cycliste double le deuxième. Il devient le..?", ["deuxieme", "deuxième", "le 2ème", "2e", "2ème", "le deuxieme", "le deuxième"]),
        ("Je commence la nuit et je termine le matin. Qui suis-je ?", ["le n", "n", "N", "le N", "c'est n", "c'est le n", "c'est N", "c'est le N"]),
        ("Un père et son fils ont 36 ans à eux deux. Le père a 30 ans de plus que son fils, quel âge a le fils?", ["3 ans", "il a 3 ans", "3","trois ans","trois","il a trois ans"]),
        ("Hier je fus, demain je serai. Qui suis-je ?", ["aujourd'hui","le présent"]),
        ("Quand Justinien avait 8 ans, son frère avait la moitié de son âge. Maintenant, Justinien a 14 ans. Quel âge a son frère ?", ["10 ans", "il a 10 ans", "10","dix ans","dix","il a dix ans","son frère a 10 ans","son frère a dix ans", "le frère a 10 ans", "le frère a dix ans", "son âge est 10 ans"])
    ]

    # Choisir une énigme aléatoire
    question, reponses_possibles = random.choice(enigmes)

    # Poser l'énigme
    print("Le Sphinx :", question)

    # Récupérer et nettoyer la réponse de l'utilisateur
    reponse_utilisateur = input("Votre réponse : ").strip().lower()

    # Vérifier si la réponse est correcte
    if reponse_utilisateur in [rep.lower() for rep in reponses_possibles]:
        print("Bravo, tu as trouvé !")
        return True
    else:
        print("Désolé, ce n'est pas la bonne réponse.")
        return False


# Programme principal

def introduction():
    """
    Introduction au jeu avec le contexte mythologique.
    """
    print("""
    ================================================
                LE LABYRINTHE DU MINOTAURE
    ================================================
    
    Digne enfant de l’illustre roi Égée, on t'a désigné pour une mission périlleuse. 
    Le Labyrinthe du roi Minos, construit par le génial Dédale, 
    t’attend avec ses secrets insondables. 

    Ce lieu est une prison pour le MINOTAURE, créature mi-homme mi-taureau, 
    mais aussi pour quiconque ose y pénétrer.  
    On dit que nulle âme ne s’en est jamais échappée... sauf peut-être grâce 
    à l’aide du légendaire Fil d’Ariane, qui pourrait te garantir une sortie. 

    Tes objectifs sont clairs :
      - Échapper au MINOTAURE qui rôde, affamé et implacable.
      - Collecter TOUS les artefacts précieux abandonnés par les victimes du MINOTAURE.
      - Résoudre les énigmes du redoutable Sphinx, gardien de passages interdits.

    Si tu parviens à atteindre la sortie sacrée, tu échapperas à une mort certaine 
    et retourneras en légende dans ton royaume.

    Que les dieux te soient favorables...
    
    """)
    input("\nAppuie sur [Entrée] pour entrer dans le Labyrinthe...")


introduction()

while True:
    
    size = 20
    maze = create_maze(size)
    dico = {0: " ", 1: "#", 2: "S"}
    pers = create_perso((0, 0))
    items = create_items(maze, random.randint(1, 10))
    mino = place_minotaure(maze,pers)
    
    if fil_d_ariane(maze, pers, items):
        print("Labyrinthe valide généré ! Le Fil d'Ariane vous garanti une sortie possible: Bonne Chance !")
        break
    else:
        print(" Le Fil D'ariane n'est pas vérifié. Impossible de rejoindre la sortie et de collecter tous les objets. Veuillez patienter. Regénération...")

while True:
    draw_maze_with_char_and_items(maze, dico, pers, items, mino)
    update_objects(pers, items)
    d = input("Entrez une direction (z=haut, s=bas, q=gauche, d=droite) : ")
    update_p(maze, d, pers)
    collect_items(pers,items)
    move_minotaure(maze, mino, pers)
    
    if minotaure_collision(pers, mino):
        print("Le Minotaure t'a attrapé ! Fin du jeu.")
        break

    if enfin_sortie(pers, maze) and not items:
        print("Tu as atteint la sortie. Mais le Sphinx te bloque le chemin !")
        if pose_enigme():
            print("Le Sphinx te laisse passer. Tu as gagné !")
            break
        else:
            print("Le Sphinx te dévore pour ton échec. Fin du jeu.")
            break
    elif enfin_sortie(pers, maze):
        print("Il te reste encore des artefacts à ramasser avant de sortir. Hâte-toi, le MINOTAURE rôde...")



