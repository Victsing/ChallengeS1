const express = require('express');
const cors = require('cors');
const app = express();
const Stripe = require('stripe')

app.use(
  cors({
    origin: `http://localhost:3000`,
    credentials: true
  })
);


app.use(express.json());
app.use(
  express.urlencoded({
    extended: true
  })
);

const stripe = new Stripe(process.env.STRIPE_SECRET_KEY)

app.post("/stripe/webhook", express.raw({type: "application/json"}), async (req, res) =>{
  const signature = req.headers["stripe-signature"]
  let event;
  try {
    event = stripe.webhooks.constructEvent(
      req.body,
      signature,
      process.env.STRIPE_SECRET_KEY
    )
  } catch (e) {
    res.status(400).send(`Webhook Error: ${e.message}`)
  }
  switch (event.type) {
    case "payment_intent.succeeded":
      const paymentIntent = event.data.object
      console.log("PaymentIntent was successful")
      break
    case "payment_method.attached":
      const paymentMethod = event.data.object
      console.log("PaymentMethod was attached to a customer!")
      break
    default:
      console.log(`Unhandled event type ${event.type}`)
  }
  res.json({received: true})
});

app.post("/stripe", async (request, response) => {
  const { amount } = request.body;

  try {
    const paymentIntent = await stripe.paymentIntents.create({
      amount,
      currency: "euro"
    });

    response.status(200).send({ secret: paymentIntent.client_secret });
  } catch (error) {
    console.log("error", error);
    response.status(500).send("error" + error);
  }
});

app.post('/api/check-compagny', async (req, res) => {
  try {
    const { name, address, founder, creationDate } = req.body;
    const siretLength = req.body.siret.length;
    const siret = parseInt(req.body.siret);
    const today = new Date().getTime();
    if (!name || !address || !founder || !siret || !creationDate) {
      return res.status(422).json({ message: 'missing data' });
    }

    if (
      typeof name !== 'string' ||
      typeof address !== 'string' ||
      typeof founder !== 'string' ||
      typeof siret !== 'number' ||
      typeof creationDate !== 'string'
    ) {
      return res.status(422).json({ message: 'invalid data type' });
    }

    if (siretLength !== 14) {
      return res.status(422).json({ message: 'invalid siret length' });
    }
    const creationDateMs = new Date(creationDate).getTime();
    if (creationDateMs > today) {
      return res.status(422).json({ message: 'invalid creation date' });
    }

    return res.status(200).json({ message: 'Entreprise valide' });
  } catch (error) {
    res.sendStatus(500, { message: 'Internal server error' });
    console.error(error);
  }
});

app.listen(5111, async () => {
  console.log('Server listening on port 5111');
});


