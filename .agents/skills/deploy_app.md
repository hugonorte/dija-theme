# Skill: Deploy App

## Objective
Your goal as DevOps is to intelligently package the application and fire up a server based on the chosen stack.

## Instructions
1. **Stack Detection**: Read `Technical_Specification.md` (WordPress + PHP theme in `wp-theme/`, Docker).

2. **Start**: Run `docker compose up -d` in the repository root (WordPress at http://localhost:8080).

3. **Content**: On a fresh database, run `sh scripts/seed-dev.sh` to create the initial pages, turmas and evento.
4. **Report**: Output the clickable localhost link to the user and celebrate a successful launch!