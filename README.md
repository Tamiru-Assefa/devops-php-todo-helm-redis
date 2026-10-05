#  Kubernetes + Helm + Jenkins + Redis — PHP Todo Application

A production-style DevOps project demonstrating how to containerize, deploy, automate, and monitor a PHP Todo application using **Docker, Kubernetes, Helm, Jenkins, Redis, MySQL, and Prometheus/Grafana**.

---

##  Project Objective

The goal of this project is to build an automated Kubernetes-based deployment environment for a PHP Todo application.

The project demonstrates how a real-world application can move from:

**Source Code → CI/CD → Container Image → Kubernetes Deployment → Monitoring**

The application uses **Redis as a caching layer** to reduce unnecessary database queries and improve application performance.

The project also demonstrates Kubernetes best practices such as:

* Containerized application deployment
* Kubernetes namespaces
* Helm-based deployment
* Automated CI/CD with Jenkins
* Docker image build, test, and push
* Kubernetes Secrets
* ConfigMaps
* Persistent Volumes
* Redis caching
* MySQL database
* Liveness and readiness probes
* Application replicas
* Prometheus monitoring
* Grafana dashboards
* Alertmanager
* Kubernetes state monitoring

---

##  Architecture

![img](ScreenShots/Architecture.jpg)
---

##  Application Request Flow

Redis is used as a caching layer between the PHP application and MySQL.

```text
User
 │
 ▼
PHP Application
 │
 ▼
Check Redis
 │
 ├── Cache HIT ──────► Return cached data
 │
 └── Cache MISS
          │
          ▼
        MySQL
          │
          ▼
     Store in Redis
          │
          ▼
     Return data
```

This reduces repeated database queries when the requested data is already available in Redis.

---

#  Technology Stack

| Component          | Technology         |
| ------------------ | ------------------ |
| Application        | PHP 8.2            |
| Web Server         | Apache             |
| Database           | MySQL 8.4          |
| Cache              | Redis 7            |
| Containerization   | Docker             |
| Orchestration      | Kubernetes         |
| Package Management | Helm               |
| CI/CD              | Jenkins            |
| Container Registry | Docker Hub         |
| Monitoring         | Prometheus         |
| Visualization      | Grafana            |
| Alerting           | Alertmanager       |
| Kubernetes Metrics | kube-state-metrics |
| Node Metrics       | Node Exporter      |
| Storage            | Kubernetes PVC     |
| Configuration      | ConfigMap          |
| Secrets            | Kubernetes Secret  |

---

#  Project Structure

```text
devops-php-todo-helm-redis/
│
├── Apps/
│   ├── index.php
│   ├── add.php
│   ├── edit.php
│   ├── delete.php
│   ├── db.php
│   ├── redis.php
│   └── styles.css
│
├── helm-chart/
│   ├── Chart.yaml
│   ├── values.yaml
│   ├── files/
│   │   └── taskdb.sql
│   └── templates/
│       ├── deployment.yaml
│       ├── service.yaml
│       ├── mysql-statefulset.yaml
│       ├── mysql-service.yaml
│       ├── redis-deployment.yaml
│       ├── redis-service.yaml
│       ├── mysql-init-configmap.yaml
│       ├── secret.yaml
│       └── ...
│
├── Jenkins/
│   ├── Dockerfile
│   └── kubeconfig/
│
├── Dockerfile
├── Jenkinsfile
└── README.md
```

> `Jenkins/kubeconfig/` contains local Kubernetes access configuration and must not be committed to GitHub.

---

#  Environment Setup

The project can run locally or on an AWS-based environment.

## Option 1 — Local Development

Recommended for development.

### Required

* Windows/Linux/macOS
* Docker Desktop
* Kubernetes enabled in Docker Desktop
* kubectl
* Helm
* Git
* Jenkins
* Docker Hub account

Verify:

```bash
docker --version
kubectl version --client
helm version
git --version
```

Make sure Kubernetes is running:

```bash
kubectl get nodes
```

---

## Option 2 — AWS Environment

For a cloud/production-style environment, an AWS EC2 instance can be used.

Create an EC2 instance with enough resources for Docker, Kubernetes, Jenkins, and monitoring.

Recommended starting point:

```text
OS: Ubuntu Server 24.04 LTS
CPU: 4 vCPU+
RAM: 16 GB+
Storage: 40 GB+
```

Install:

```text
Docker
kubectl
Helm
Git
```

Then configure the Kubernetes environment and deploy the project.

> Monitoring components such as Prometheus and Grafana can consume significant memory, so a small EC2 instance is not recommended for the complete stack.

---

#  Deployment

Clone the repository:

```bash
git clone https://github.com/Tamiru-Assefa/devops-php-todo-helm-redis.git
cd devops-php-todo-helm-redis
```

Create the namespace:

```bash
kubectl create namespace devops-todo-helm
```

Deploy using Helm:

```bash
helm upgrade --install devops-todo ./helm-chart \
  --namespace devops-todo-helm
```

Check the application:

```bash
kubectl get pods -n devops-todo-helm
```

```bash
kubectl get services -n devops-todo-helm
```

---

#  CI/CD Pipeline

Jenkins automates the deployment process.

```text
GitHub
   ↓
Checkout
   ↓
Docker Build
   ↓
Docker Test
   ↓
Docker Hub Push
   ↓
Helm Lint
   ↓
Helm Deploy
   ↓
Kubernetes Rollout
   ↓
Deployment Verification
```

The Docker image is automatically tagged using the Jenkins build number.

Example:

```text
ybtamiru/devops-php-todo-helm:3
```

---

#  Monitoring

The monitoring stack runs in a separate Kubernetes namespace:

```text
monitoring
```

Components:

```text
Prometheus
Grafana
Alertmanager
kube-state-metrics
Node Exporter
```

Monitoring flow:

```text
Kubernetes
    │
    ├── Node Exporter
    │
    └── kube-state-metrics
             │
             ▼
         Prometheus
          /      \
         ▼        ▼
     Grafana   Alertmanager
```

Grafana can be accessed locally using:

```bash
kubectl port-forward svc/prometheus-stack-grafana \
  -n monitoring 3000:80
```

Then open:

```text
http://localhost:3000
```

---

#  Security

The project uses Kubernetes Secrets for sensitive database credentials instead of storing passwords directly inside `values.yaml`.

Sensitive configuration such as:

```text
MySQL password
Kubernetes kubeconfig
Docker Hub credentials
```

should never be committed to GitHub.

---

#  Health Checks

The PHP application includes Kubernetes:

### Liveness Probe

Checks whether the application is still alive.

### Readiness Probe

Checks whether the application is ready to receive traffic.

This allows Kubernetes to automatically manage unhealthy application instances.

---

#  Scalability

The PHP application runs with three replicas:

```text
PHP Replica 1
PHP Replica 2
PHP Replica 3
```

Kubernetes can distribute application traffic across the replicas.

Redis provides caching, while MySQL uses persistent storage through a Kubernetes PVC.

---

#  What This Project Demonstrates

This project demonstrates practical knowledge of:

* Docker
* Kubernetes
* Helm
* Jenkins CI/CD
* Docker Hub
* Redis
* MySQL
* Kubernetes Secrets
* ConfigMaps
* Persistent Volumes
* Health probes
* Kubernetes Services
* Application scaling
* Prometheus
* Grafana
* Alertmanager
* Kubernetes monitoring
* Infrastructure automation
* DevOps workflow

---

#  Future Enhancement

The core project is complete.

An optional future enhancement is:

```text
GitHub Push
     ↓
GitHub Webhook
     ↓
Jenkins
     ↓
Automatic CI/CD Pipeline
```

This would remove the need to manually trigger the Jenkins pipeline after every GitHub push.

---

#  Author

**Tamiru Assefa**

Computer Science Student | DevOps & Cloud Enthusiast

GitHub:
https://github.com/Tamiru-Assefa
