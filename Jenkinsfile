pipeline {
    agent any

    environment {
        IMAGE_NAME = 'ybtamiru/devops-php-todo-helm'
        IMAGE_TAG  = "${BUILD_NUMBER}"
        NAMESPACE  = 'devops-todo-helm'
        RELEASE    = 'devops-todo'
    }

    stages {

        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('Build Docker Image') {
            steps {
                sh '''
                    docker build \
                      -t ${IMAGE_NAME}:${IMAGE_TAG} \
                      .
                '''
            }
        }

        stage('Test Docker Image') {
            steps {
                sh '''
                    docker run --rm \
                      ${IMAGE_NAME}:${IMAGE_TAG} \
                      php -m | grep -E "mysqli|redis"
                '''
            }
        }

        stage('Push Docker Image') {
            steps {
                withCredentials([
                    usernamePassword(
                        credentialsId: 'dockerhub-credentials',
                        usernameVariable: 'DOCKER_USERNAME',
                        passwordVariable: 'DOCKER_PASSWORD'
                    )
                ]) {
                    sh '''
                        echo "$DOCKER_PASSWORD" | docker login \
                          -u "$DOCKER_USERNAME" \
                          --password-stdin

                        docker push ${IMAGE_NAME}:${IMAGE_TAG}

                        docker logout
                    '''
                }
            }
        }

        stage('Helm Lint') {
            steps {
                sh '''
                    helm lint ./helm-chart
                '''
            }
        }

        stage('Helm Deploy') {
            steps {
                sh '''
                    helm upgrade --install ${RELEASE} ./helm-chart \
                      --namespace ${NAMESPACE} \
                      --set image.repository=${IMAGE_NAME} \
                      --set image.tag=${IMAGE_TAG}
                '''
            }
        }

        stage('Verify Deployment') {
            steps {
                sh '''
                    kubectl rollout status \
                      deployment/devops-todo-devops-php-todo \
                      -n ${NAMESPACE} \
                      --timeout=180s

                    kubectl get pods -n ${NAMESPACE}
                '''
            }
        }
    }
}