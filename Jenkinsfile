pipeline {
  environment {
    DOCKER_IMAGE = "nexus01.leekie.com:8483/zircon_adapter_api"
    DEPLOYMENT = "zircon_adapter_api"
  }
  agent any
  stages {
    stage('Get Version') {
      steps {
        script {
          env.COMMIT = sh (
            script: 'cat Dockerfile |grep "ENV APP_VERSION" | sed "s/[A-Z_= ]//g"',
            returnStdout: true
          ).trim()
          currentBuild.displayName = "${env.COMMIT}"
          currentBuild.description = "..."
        }
      }
    }

    stage('Build Image') {
      when { branch "trunk" }
      steps {
        sh "svn upgrade"
        sh "docker build -t " + DOCKER_IMAGE +":${env.COMMIT} ."
        sh "docker tag " + DOCKER_IMAGE + ":${env.COMMIT} " + DOCKER_IMAGE + ":latest"
      }
    }

    stage('Push Image') {
      when { branch "trunk" }
      steps {
        script {
          withDockerRegistry([credentialsId: 'qauser', url: "https://nexus01.leekie.com:8483"]) {
            sh "docker push " + DOCKER_IMAGE +":${env.COMMIT}"
            sh "docker push " + DOCKER_IMAGE +":latest"
          }
        }
      }
    }	

    stage('Deploy To Staging') {
      when { branch "trunk" }
      steps {
        milestone(1)
        withKubeConfig([credentialsId:'zircon-stg', serverUrl: 'https://zks-stg.leekie.com:6443']){
        sh """
          envsubst '\${DOCKER_IMAGE} \${COMMIT}' < deployment.yaml  > deployments.yaml
          kubectl -n zircon apply -f deployments.yaml
        """
          }
      }
    }

    stage('Deploy To UAT') {
      when { not {branch "trunk"} }
      steps {
        milestone(1)
        withKubeConfig([credentialsId:'zircon-uat', serverUrl: 'https://zks-uat.leekie.com:6443']){
        sh """
          envsubst '\${DOCKER_IMAGE} \${COMMIT}' < deployment.yaml  > deployments.yaml
          kubectl -n zircon apply -f deployments.yaml
        """
        }
      }
    }

    stage('Deploy To Production') {
      when { not {branch "trunk"} }
      steps {
        script {
          emailext to: 'onyx.api@leekie.com',
          from: 'jenkins@leekie.com',
          subject: "${env.JOB_NAME} - (${env.BUILD_NUMBER}) Production Deployment",
          body: "${currentBuild.currentResult}: Job ${env.JOB_NAME} build ${env.BUILD_NUMBER}\nPlease go to console output of ${env.BUILD_URL} to Approve or Reject."
          timeout(time: 100, unit: "DAYS"){ 
            input message: 'Do you want to approve the deploy?', ok: 'Yes'
          }
          withKubeConfig([credentialsId:'zircon-prod', serverUrl: 'https://zks-prod.leekie.com:6443']){
          sh """
            printenv
            envsubst '\${DOCKER_IMAGE} \${COMMIT}' < deployment.yaml  > deployment-test.yaml
            kubectl -n zircon apply -f deployment-test.yaml
          """
          }
        }
      }
    }
  }

  post {
    failure {
      emailext body: "Please check on revision ${env.COMMIT}", subject: "Build failed on $DEPLOYMENT", to: "onyx.api@leekie.com"
    }
  }
}