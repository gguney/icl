# Insider 🦄 Champions League
# Setup

- If you need change `APP_PORT` inside .env.example. change it before setup. Default is 86.
- Run `sh setup.sh`
- I also added open address command for the setup and start scripts. If your browser does not open. Please move to the next item.
- Visit `localhost:86`, if you changed your APP_PORT visit `localhost:APP_PORT`
- After open the app you will see 4 teams.
- Push `Generate` Button on `Fixtures` card and play your fixture with `Play Next Week` and `Play All Weeks` buttons.

# Notes

I creted Insider Champions League with Docker, Laravel, VueJS and TailwindCSS. I used Circle method for round-robin tournament.

# Description

In this project, we expect you to complete a simulation. In this simulation, there will be a group of football teams and the simulation will show match results and the league table. Your task is to estimate the final league table.
League Rules:
- There will be four teams in the league (if you wish, you can choose teams that have different strengths and you can determine the results of the matches depending on the strengths of these selected teams).
- Other rules in the league (scoring, points, goal difference, etc.) will be the same as the rules of the Premier League
  http://www.premierleague.com/en-gb/matchday/league-table.html

As can be seen in Figure 1.a and Figure 2.a, each screen of the subsequent week will represent the league table and updated the match status. Moving forward, both the point scores and the results of the matches will be represented by this screen. In addition, after the 4th week, your estimation will also be represented on this screen.

# Limitations / Requirements:
- The Project needs to be completed using PHP (Projects that are completed in JAVA, .net, Ruby, etc. will not be taken into consideration.).
- Please use OOP.
- You will be expected to use Javascript or its frameworks where applicable.
- Please deploy your Project and share your project’s access link. (Github, Bitbucket or Gitlab)
- We expect you to send your code at the end of the project.
  Extras (Including the following extras to your project will be regarded as a strong plus)
- After you click on the all-League Play button, play the matches automatically until the end of the League and list the results of the matches by weeks.
- To use modern Javascript frameworks.
- Edit the results of the matches and calculate the edited results of the matches based on the content of the modified standings.
- Automated Unit Tests.

# FAQ for PHP Project
###1. What are Football Rules?
   Three points for a win is a standard used in many sports leagues and group tournaments, especially in association football, in which three (rather than two) points are awarded to the team winning a match, with no points awarded to the losing team. If the game is drawn, each team receives one point. The system places additional value on wins compared to draws such that teams with a higher number of wins may rank higher in tables than teams with a lower number of wins but more draws. Source
###2. What is a league?
   The tournament proper begins with a group stage of 32 teams, divided into eight groups of four. Seeding is used whilst making the draw for this stage, whilst teams from the same nation may not be drawn into groups together. Each team plays six group stage games, meeting the other three teams in its group home and away in a round-robin format. Source
###3. What is a champions league?
   The UEFA Champions League (abbreviated as UCL) is an annual club football competition organised by the Union of European Football Associations (UEFA) and contested by top-division European clubs, deciding the competition winners through a round robin group stage to qualify for a double-legged knockout format, and a single leg final. It is one of the most prestigious football tournaments in the world and the most prestigious club competition in European football, played by the national league champions (and, for some nations, one or more runners-up) of their national associations. Source
###4. What is a fixture?
   The teams will be split into four seeding pots. Pot 1 will consist of the holders, the UEFA Europa League winners and the champions of the six highest-ranked nations who did not qualify via one of the 2020/21 continental titles; Pots 2 to 4 will be determined by the club coefficient rankings.
   No team can play a side from their own association. Any other restrictions will be announced ahead of the draw ceremony.
   In the case of associations with two representatives, clubs will be paired in order to split their matches between Tuesdays and Wednesdays. In the case of associations with four (or more) representatives, two pairings will be made. Source
###5. What exactly do you want from the simulation?
   It needs to simulate the matches week by week from the fixture created before. When two teams play matches against each other, let's say team A has 100 team power and team B has 10 team power, in this case in real-world team B can't win a match against team A, simulation also should consider the team power. But, that's not mean team B never can win a match, it might happen as well but with a really small chance
###6. Which team will be the winner on what?
   A team playing a match must have a power point of its own. With this point, it should win according to its superiority against the other team, depending on whether it is home or away, supporter strength, goalkeeper factor and other factors that you will determine yourself. Here, if you want, you can gain superiority according to the number of goals scored by the teams, or you can make direct score predictions. But what you need to pay attention to is that the results are close to the truth.
###7. Should the design be the same?
   No
###8. How does prediction work?
   When entering the last 3 weeks during the group matches, we want the championship rates of the teams to be estimated. You are expected to create a certain championship percentage by taking into account the remaining matches of the teams, either directly taking into account the points earned at that time, or by adding the remaining matches of the teams along with these points to this forecasting system. For example, there are 2 weeks left of the group matches and 1 team is ahead by 9 points. In this case, the championship percentage of that team will be 100% and the others will decrease to 0% or there is 1 week left until the end of the group matches and the points of the teams in the first two rows of the group will be equal and the last match will be played against each other. Here, estimates such as 50%, 50% or 65%, 35% can be made based on the goals they have scored in their past matches or the teams they have beaten. This part depends on the estimation algorithm you will write.
