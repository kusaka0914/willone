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
  "desiredCount": 2,
  "enableECSManagedTags": true,
  "enableExecuteCommand": false,
  "launchType": "FARGATE",
  loadBalancers: [
    {
      containerName: 'laravel',
      containerPort: 80,
      targetGroupArn: 'arn:aws:elasticloadbalancing:ap-northeast-1:622803604774:targetgroup/www-willone-jp/774171bfff6cf9bc'
    },
    {
      containerName: 'laravel',
      containerPort: 80,
      targetGroupArn: 'arn:aws:elasticloadbalancing:ap-northeast-1:622803604774:targetgroup/www-jinzaibank-com-woa/a340eb1ec76eafa5'
    },
    {
      containerName: 'laravel',
      containerPort: 80,
      targetGroupArn: 'arn:aws:elasticloadbalancing:ap-northeast-1:622803604774:targetgroup/kurohon-jp-woa/1de58175d6cd297c'
    }
  ],
  "networkConfiguration": {
    "awsvpcConfiguration": {
      "assignPublicIp": "DISABLED",
      "securityGroups": [
        "sg-0db82d839fa5582e1"
      ],
      "subnets": [
        "subnet-0df8bd0b4a35dfe71",
        "subnet-0de7cdfc8d328f2d3"
      ]
    }
  },
  "platformFamily": "Linux",
  "platformVersion": "LATEST",
  "propagateTags": "NONE",
  "schedulingStrategy": "REPLICA"
}