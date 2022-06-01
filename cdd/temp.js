IMAGE="./192168012202-2-674-20190930103902-00001.jpg"
LOCATION="“Œ“ú–{"
VISUALFEATURES="Categories"
DETAILS=""
LANGUAGE="ja"
APIKEY="e4a09586c63348969d9562cf64631006"

curl -s -X POST "https://${LOCATION}.api.cognitive.microsoft.com/vision/v1.0/analyze?visualFeatures=${VISUALFEATURES}&details=${DETAILS}&language=${LANGUAGE}" -H "Content-Type: application/octet-stream" -H "Ocp-Apim-Subscription-Key: ${APIKEY}" --data-binary "@${IMAGE} -O a.txt"
