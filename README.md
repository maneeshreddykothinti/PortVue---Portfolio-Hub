Db commands are provided below

PortVue---Portfolio-Hub
The system provides a centralized solution where jobseekers can showcase their work in an organized manner and recruiters can easily view, compare, and assess portfolios, simplifying the hiring process. What’s New? – ➢ Portfolios are integrated with downloadable resumes, enabling easy sorting and evaluation—unlike traditional platforms, PortVue is fully portfolio-centric. ➢ Introduces secure classroom spaces where institutions (e.g., colleges) can collect and manage portfolios from a limited, authenticated group of users.

For a breif walkthrough on whats my project about:- ➢ A portfolio hosting and posting website where jobseakers can uplooad their portfolios along with resumes and recruiters can have easily have a look over it. ➢ downloading the resumes gives an easier classifications for jobs. ➢ also helps as hosting : - here i have just used local sever but later it can be integrated with cloud ➢ For a secure classrooms (peircing short no of candidates like colleges) :- collect and manage portfolios from a limited, authenticated group of users.(just like google classrooms but with a webpage preview option)

Future optimizations:- ➢ cloud integration ➢ ml models for searching ➢ ai verification to check if its really a portfolio or a website

how my code works (directory explanations ):- ➢ login page :- consists of login and signup forms ➢ interface page :- main page for exploring portfolios, finding top rated portfolios, upload section, profile section, navigation for classrooms ➢ categories_iframe & top_iframe:- i developed the working of picking top rated and classifications for domain specific....... seperately and integrated using iframes ➢ classrooms :- contains of 3 options :- create, join, hub(for verifying portfolios - only for admin) : create for a new classrooms , joining for uploading , hub for verifying the uploaded portfolios ➢portfolios and classprojects are just for storing the uploaded files by others (can be changed for cloud) - this project is done for only local working .. but can change it to dynamic by integrating with cloud

Data Base commands for db creation : - (handling classrooms and portfolios hosted/posted)- (Note: I have used apache and mysql using xampp for local server)

-- Database: portvuedb -- Engine: InnoDB -- Charset & Collation: utf8mb4_general_ci

/* ---------------- USERS TABLE ---------------- */ CREATE TABLE users ( uid INT(11) NOT NULL AUTO_INCREMENT, user_name VARCHAR(100) COLLATE utf8mb4_general_ci NOT NULL, user_email VARCHAR(100) COLLATE utf8mb4_general_ci NOT NULL, user_pass VARCHAR(255) COLLATE utf8mb4_general_ci NOT NULL, created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (uid), UNIQUE KEY user_email (user_email) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/* ---------------- STUDENTS TABLE ---------------- */ CREATE TABLE students ( sid INT(11) NOT NULL AUTO_INCREMENT, sroll VARCHAR(50) COLLATE utf8mb4_general_ci NOT NULL, ccode VARCHAR(50) COLLATE utf8mb4_general_ci NOT NULL, paddress VARCHAR(255) COLLATE utf8mb4_general_ci NOT NULL, PRIMARY KEY (sid), KEY ccode (ccode) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/* ---------------- CLASSES TABLE ---------------- */ CREATE TABLE classes ( cid INT(11) NOT NULL AUTO_INCREMENT, ccode VARCHAR(50) COLLATE utf8mb4_general_ci NOT NULL, pass VARCHAR(100) COLLATE utf8mb4_general_ci NOT NULL, host VARCHAR(100) COLLATE utf8mb4_general_ci NOT NULL, PRIMARY KEY (cid), UNIQUE KEY ccode (ccode) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/* ---------------- PORTFOLIOS TABLE ---------------- */ CREATE TABLE portfolios ( pid INT(11) NOT NULL AUTO_INCREMENT, uid INT(11) NOT NULL, uname VARCHAR(100) COLLATE utf8mb4_general_ci NOT NULL, paddress VARCHAR(255) COLLATE utf8mb4_general_ci NOT NULL, tags VARCHAR(300) COLLATE utf8mb4_general_ci NOT NULL, description VARCHAR(500) COLLATE utf8mb4_general_ci NOT NULL, ratings INT(11) NOT NULL, PRIMARY KEY (pid), UNIQUE KEY uname (uname) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/* ---------------- PORTFOLIO_LIKES TABLE ---------------- */ CREATE TABLE portfolio_likes ( id INT(11) NOT NULL AUTO_INCREMENT, portfolio_id INT(11) NOT NULL, user_id INT(11) NOT NULL, liked_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (id), UNIQUE KEY unique_like (portfolio_id, user_id) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
