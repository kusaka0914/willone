{
  "deploymentConfiguration": {
    "deploymentCircuitBreaker": {
      "enable": true,
      "rollback": true
    },
    "maximumPercent": 200,
    "minimumHealthyPercent": 100
  },
  "deploymentController": {
    "type": "ECS"
  },
  "desiredCount": 1,
  "enableECSManagedTags": true,
  "enableExecuteCommand": true,
  "launchType": "FARGATE",
  loadBalancers: [
    {
      containerName: 'laravel',
      containerPort: 80,
      targetGroupArn: 'arn:aws:elasticloadbalancing:ap-northeast-1:967125328503:targetgroup/development-willone-jp/41853d1203aad19f'
    },
    {
      containerName: 'laravel',
      containerPort: 80,
      targetGroupArn: 'arn:aws:elasticloadbalancing:ap-northeast-1:967125328503:targetgroup/development-jinzaibank-com-woa/0edddcae639572df'
    },
    {
      containerName: 'laravel',
      containerPort: 80,
      targetGroupArn: 'arn:aws:elasticloadbalancing:ap-northeast-1:967125328503:targetgroup/wp-stg-kurohon-jp-woa/2b7528e63f8576e5'
    }
  ],
  "networkConfiguration": {
    "awsvpcConfiguration": {
      "assignPublicIp": "DISABLED",
      "securityGroups": [
        "sg-00b2adfb81cf90cd9"
      ],
      "subnets": [
        "subnet-0e3997d59892775dd",
        "subnet-07a87f3f74e649171"
      ]
    }
  },
  "platformFamily": "Linux",
  "platformVersion": "LATEST",
  "propagateTags": "NONE",
  "schedulingStrategy": "REPLICA"
}
