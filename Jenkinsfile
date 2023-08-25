pipeline {
  agent any

  stages {
    stage ('make storage dirs') {
      steps{
        sh 'mkdir -p storage/framework/cache'
        sh 'mkdir -p storage/framework/sessions'
        sh 'mkdir -p storage/framework/testing'
        sh 'mkdir -p storage/framework/views'
      }
    }

    stage ('Run Docker Compose') {
      steps{
        sh 'docker compose down'
        sh 'docker compose up -d --build'
      }
    }

    stage ('Install Dependencies') {
      steps{
        sh 'docker exec php_jenkins sh /var/www/allximik/jenkins.sh'
      }
    }

    stage ('Run Tests') {
      steps{
        sh 'docker exec php_jenkins ./vendor/bin/phpunit'
      }
    }

    stage ('Down') {
      steps{
          sh 'docker compose down'
      }
    }
  }
}

