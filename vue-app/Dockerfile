FROM node:18-alpine

# set the working direction
WORKDIR /home/node
COPY . ./
RUN npm install

CMD ["npm", "run", "dev", "--", "--host", "0.0.0.0"]