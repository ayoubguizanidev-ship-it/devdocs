<?php
namespace App\DataFixtures;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Tag;
use App\Entity\Tutorial;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;
class AppFixtures extends Fixture
{
public function __construct(private UserPasswordHasherInterface $hasher)
{
}
public function load(ObjectManager $manager): void
{
// 1. Utilisateurs (mot de passe : password)
$users = [];
foreach (['amine' => 'Amine', 'sarra' => 'Sarra', 'youssef' =>
'Youssef'] as $login => $pseudo) {
$user = new User();
$user->setEmail($login . '@devdocs.tn');
$user->setPseudonyme($pseudo);
$user->setPassword($this->hasher->hashPassword($user,
'password'));
$manager->persist($user);
$users[] = $user;
}
// 2. Catégories
$categories = [];
$dataCategories = [
'PHP' => 'Le langage côté serveur et ses bonnes pratiques.',
'Symfony' => 'Le framework PHP : routes, Doctrine, formulaires,
sécurité.',
'Frontend' => 'HTML, CSS, Bootstrap et JavaScript.',
'DevOps' => 'Git, Docker, déploiement et intégration continue.',
'Base de données' => 'MySQL, requêtes SQL et modélisation.',
];
foreach ($dataCategories as $name => $description) {
$category = new Category();
$category->setName($name);
$category->setDescription($description);
$manager->persist($category);
$categories[] = $category;
}
// 3. Tags
$tags = [];
foreach (['Débutant', 'Intermédiaire', 'Avancé', 'Performance',
'Sécurité',
'Bonnes pratiques', 'Astuce', 'Tutoriel vidéo', 'Mise à
jour', 'Projet'] as $name) {
$tag = new Tag();
$tag->setName($name);
$manager->persist($tag);
$tags[] = $tag;
}
// 4. Tutoriels (15)
$titles = [
'Installer Symfony 7.4 pas à pas', 'Comprendre les routes
Symfony', 'Créer sa première entité Doctrine',
'Les relations ManyToOne expliquées', 'Valider un formulaire avec
Assert', 'Sécuriser une application avec les Voters',
'Envoyer des e-mails avec Mailer', 'Bootstrap 5.3 : la grille
responsive', 'Les Cards et Badges Bootstrap',
'Git : bien écrire ses commits', 'Travailler avec des branches
Git', 'Docker pour les développeurs PHP',
'Les jointures SQL simplement', 'Optimiser ses requêtes
Doctrine', 'Les nouveautés de PHP 8.3',
];
$slugger = new AsciiSlugger();
$tutorials = [];
foreach ($titles as $i => $title) {
$tutorial = new Tutorial();
$tutorial->setTitle($title);
$tutorial->setSlug(strtolower($slugger->slug($title)));
$tutorial->setContent("Dans ce tutoriel, nous allons voir :
$title. "
. "Chaque étape est expliquée avec des exemples de code
simples à reproduire.");
$tutorial->setCreatedAt(new \DateTimeImmutable('-' . (15 - $i) .
' days'));
$tutorial->setAuthor($users[$i % 3]);
$tutorial->setCategory($categories[$i % 5]);
$tutorial->addTag($tags[$i % 10]);
$tutorial->addTag($tags[($i + 3) % 10]);
$manager->persist($tutorial);
$tutorials[] = $tutorial;
}
// 5. Commentaires (30 = 2 par tutoriel)
$phrases = [
'Merci, très clair et bien expliqué !', 'Exactement ce que je
cherchais.',
'Petite question : est-ce que ça marche aussi avec Symfony 6 ?',
'Super tuto, j\'ai tout compris.',
'Il manque peut-être un exemple complet à la fin.', 'Top, je
l\'ai testé et ça fonctionne.',
];
foreach ($tutorials as $i => $tutorial) {
for ($j = 0; $j < 2; $j++) {
$comment = new Comment();
$comment->setContent($phrases[($i + $j) % 6]);
$comment->setAuthor($users[($i + $j + 1) % 3]);
$comment->setTutorial($tutorial);
$comment->setCreatedAt(new \DateTimeImmutable('-' . (14 - $i)
. ' days +' . $j . ' hours'));
$manager->persist($comment);
}
}
$manager->flush();
}
}