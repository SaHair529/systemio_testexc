curl --location --request POST 'http://localhost:8000/calculate-price' \
--header 'Content-Type: application/json' \
--data-raw '{
"product": 1,
"taxNumber": "DE123456789"
}'

curl --location --request POST 'http://localhost:8000/purchase' \
--header 'Content-Type: application/json' \
--data-raw '{
"product": 3,
"taxNumber": "DE123456789",
"paymentProcessor": "paypal"
}'