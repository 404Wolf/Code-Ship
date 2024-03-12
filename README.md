# Codeship 

Code ship is a collaborative code-sharing platform where users can share code in real-time. The platform is dockerized and utilizes JavaScript and PHP to create a synchronized textbox experience among multiple clients. The synchronization is achieved through polling a `contents.json` file, ensuring that all clients stay up-to-date with the latest code changes.

## Features

- **Real-time Code Synchronization:** The textbox allows multiple users to collaborate on code simultaneously, with changes reflected in real-time across all connected clients.

- **Dockerized Environment:** The application is containerized using Docker, providing a consistent and reproducible environment for deployment.

- **Polling `contents.json`:** Code synchronization is facilitated by regularly polling the `contents.json` file, which contains the latest code changes. This ensures that all clients are aware of the current state of the code.

## Technologies Used

- **JavaScript (Frontend):** The client-side logic is implemented using JavaScript to handle user interactions and update the code textbox in real-time.

- **PHP (Backend):** PHP is used to handle server-side logic, manage file operations, and serve the `contents.json` file for code synchronization.

- **Docker:** The application is containerized using Docker, allowing for easy deployment and scalability.

## Getting Started

### Prerequisites

- Docker engine.

### Installation

1. Clone the repository: `git clone https://github.com/404Wolf/Code-Ship.git`

2. Navigate to the project directory: `cd Code-Ship`

3. Build the Docker image: `docker compose up`

4. Access the application in your web browser: `http://0.0.0.0:8080

## License

This project is licensed under the MIT License.
