const express = require('express');
const cors = require('cors');
const app = express();

app.use(
  cors({
    origin: 'http://localhost:3000',
    credentials: true
  })
);

app.use(express.json());
app.use(
  express.urlencoded({
    extended: true
  })
);

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
